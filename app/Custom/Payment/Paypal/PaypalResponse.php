<?php

namespace App\Custom\Payment\Paypal;

use App\Custom\Payment\Contracts\ResponseContract;
use App\Custom\Payment\Response as PaymentResponse;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PaypalResponse extends PaymentResponse implements ResponseContract
{
	public function __construct(string $token)
	{
		parent::__construct($token);
		$this->paymentMode = config('services.paypal.mode');
	}

	/**
	 * Get client id according to payment mode
	 *
	 * @return string
	 */
	private function getClientId(): string
	{
		return $this->paymentMode === 'live'
			? config('services.paypal.live_client_id')
			: config('services.paypal.test_client_id');
	}

	/**
	 * Get client secret according to payment mode
	 *
	 * @return string
	 */
	private function getClientSecret(): string
	{
		return $this->paymentMode === 'live'
			? config('services.paypal.live_client_secret')
			: config('services.paypal.test_client_secret');
	}

	/**
	 *  Get paypal root url according to payment mode
	 *
	 * @return string
	 */
	private function getRootUrl(): string
	{
		return $this->paymentMode === 'live'
			? 'https://api.paypal.com'
			: 'https://api.sandbox.paypal.com';
	}

	/**
	 * Get payment detail according to id/token
	 *
	 * @param string $token
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function getPaymentDetail(string $token): array
	{
		$accessToken = $this->getAccessToken();

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->getRootUrl() . "/v1/payments/payment/{$token}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

		$headers   = [];
		$headers[] = "Content-Type: application/json";
		$headers[] = "Authorization: Bearer {$accessToken}";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response   = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			throw new Exception('Something went wrong. Please try again later.', $statusCode);
		}
		curl_close($ch);

		$result = json_decode($response, true);

		if ($statusCode !== Response::HTTP_OK) {
			throw new Exception($this->errorMessage($result), $statusCode);
		}

		return $result;
	}

	/**
	 * Get access token to access payment detail
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function getAccessToken(): string
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->getRootUrl() . "/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $this->getClientId() . ":" . $this->getClientSecret());

		$headers   = [];
		$headers[] = "Accept: application/json";
		$headers[] = "Accept-Language: en_US";
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response   = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if (curl_errno($ch)) {
			throw new Exception('Something went wrong. Please try again later.', $statusCode);
		}
		curl_close($ch);

		$result = json_decode($response, true);

		if ($statusCode !== Response::HTTP_OK) {
			throw new Exception($result['error'] . ': ' . $result['error_description'], $statusCode);
		}

		return $result['access_token'];
	}

	/**
	 * Returns the error message on payment verification
	 *
	 * @param $error
	 *
	 * @return string
	 */
	private function errorMessage($error): string
	{
		return $error['message'];
	}

	/**
	 * Must return 'approved' if the payment has been successfully completed.
	 *
	 * @return string
	 */
	public function status(): string
	{
		return $this->response['state'];
	}

	/**
	 * Returns the Carbon instance of the payment creation date (i.e. paid date)
	 *
	 * @return Carbon
	 */
	public function createdAt(): Carbon
	{
		return Carbon::parse($this->response['create_time']);
	}

	/**
	 * Returns the total amount in the type (Rs|Paisa|$|Cents) as required by the payment provider
	 *
	 * @return mixed
	 */
	public function totalAmount()
	{
		return (float) $this->response['transactions'][0]['amount']['total'];
	}

	/**
	 * Returns the currency used while doing payment
	 *
	 * @return string
	 */
	public function currency(): string
	{
		return $this->response['transactions'][0]['amount']['currency'];
	}
}
