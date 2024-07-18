<?php

namespace App\Custom\Payment;

use Exception;

abstract class Response
{
	protected $response;
	protected $paymentMode;

	public function __construct(string $token)
	{
		$this->response    = $this->getPaymentDetail($token);
		$this->paymentMode = 'test';
	}

	/**
	 * Get payment detail according to id/token
	 *
	 * @param string $token
	 *
	 * @return array
	 * @throws Exception
	 */
	abstract protected function getPaymentDetail(string $token): array;

	/**
	 * Return response from the api
	 *
	 * @return array
	 */
	public function rawData(): array
	{
		return $this->response;
	}
}
