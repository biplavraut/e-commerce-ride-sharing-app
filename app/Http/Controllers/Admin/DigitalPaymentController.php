<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\PaymentLogService;
use App\Http\Controllers\Controller;
use App\Custom\Payment\Esewa\EsewaResponse;

class DigitalPaymentController extends CommonController
{
    /** @var PaymentLogService */
    private $paymentLogService;

    public function __construct(PaymentLogService $paymentLogService)
    {
        parent::__construct();
        $this->paymentLogService = $paymentLogService;
        $this->paymentMode = config('services.esewa.mode');
    }


    private function getMerchantId(): string
    {
        return $this->paymentMode === 'live'
            ? config('services.esewa.live_merchant_id')
            : config('services.esewa.test_merchant_id');
    }

    private function getMerchantSecret(): string
    {
        return $this->paymentMode === 'live'
            ? config('services.esewa.live_merchant_secret')
            : config('services.esewa.test_merchant_secret');
    }

    private function getURL(): string
    {
        return $this->paymentMode === 'live'
            ? 'https://esewa.com.np/mobile/transaction?txnRefId='
            : 'https://uat.esewa.com.np/mobile/transaction?txnRefId=';
    }

    public function khalti(Request $request)
    {
        if ($request->idx) {
            $url = "https://khalti.com/api/v2/merchant-transaction/" . $request->idx . "/";
        } else {
            $url = "https://khalti.com/api/v2/merchant-transaction/";
        }

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key ' . env('KHALTI_TEST_SECRET_KEY')];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;
    }

    public function esewa(Request $request)
    {
        $ch = curl_init();

        $url = $this->getURL() . $request->idx;

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

        curl_close($ch);

        $result = json_decode($response, true);
        return $result;
    }
}
