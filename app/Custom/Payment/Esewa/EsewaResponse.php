<?php

namespace App\Custom\Payment\Esewa;

use App\Custom\Payment\Contracts\ResponseContract;
use App\Custom\Payment\Response as PaymentResponse;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class EsewaResponse extends PaymentResponse implements ResponseContract
{
    public function __construct(string $token)
    {
        parent::__construct($token);
        $this->paymentMode = config('services.esewa.mode');
    }

    /**
     *  Get merchant id according to payment mode
     *
     * @return string
     */
    private function getMerchantId(): string
    {
        return $this->paymentMode === 'live'
            ? config('services.esewa.live_merchant_id')
            : config('services.esewa.test_merchant_id');
    }

    /**
     *  Get merchant secret according to payment mode
     *
     * @return string
     */
    private function getMerchantSecret(): string
    {
        return $this->paymentMode === 'live'
            ? config('services.esewa.live_merchant_secret')
            : config('services.esewa.test_merchant_secret');
    }

    /**
     *  Get url according to payment mode
     *
     * @return string
     */
    private function getURL(): string
    {
        return $this->paymentMode === 'live'
            ? 'https://esewa.com.np/mobile/transaction?txnRefId='
            : 'https://uat.esewa.com.np/mobile/transaction?txnRefId=';
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
        $ch = curl_init();

        $url = $this->getURL() . $token;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = [];
        $headers[] = "Content-Type: application/json";
        $headers[] = "Merchantid: " . $this->getMerchantId();
        $headers[] = "Merchantsecret: " . $this->getMerchantSecret();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            throw new Exception('Something went wrong. Please try again later.', $statusCode);
        }
        curl_close($ch);

        $result = json_decode($response, true);

        // TODO: Further checking required
        // if (isset($result['message'])) {
        //     throw new Exception($result['message']['errorMessage'], Response::HTTP_BAD_REQUEST);
        // }

        // dd($result[0]['code']);

        return $result;
    }

    /**
     * Must return 'approved' if the payment has been successfully completed.
     *
     * @return string
     */
    public function status(): string
    {
        if (isset($this->response[0]['code'])) {
            return $this->response[0]['code'] === '00' ? 'approved' : $this->response[0]['code'];
        }
        return "failure";
    }

    /**
     * Returns the total amount in the type (Rs|Paisa|$|Cents) as required by the payment provider
     *
     * @return mixed
     */
    public function totalAmount()
    {
        return $this->response[0]['totalAmount'];
    }

    /**
     * Returns the Carbon instance of the payment creation date (i.e. paid date)
     *
     * @return Carbon
     */
    public function createdAt(): Carbon
    {
        return Carbon::parse($this->response['transactionDetails']['date']);
    }

    /**
     * Returns merchant name
     *
     * @return string
     */
    public function merchantName(): string
    {
        return $this->response[0]['merchantName'];
    }
}
