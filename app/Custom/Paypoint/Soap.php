<?php

namespace App\Custom\Paypoint;

use App\CheckPaymentLog;
use App\ExecutePaymentLog;
use SimpleXMLElement;
use Illuminate\Support\Str;

class Soap
{

    private $companyCode, $serviceCode, $phoneNumber;
    private $userId, $userPassword, $salePointType, $baseURI;

    public function __construct($companyCode, $serviceCode, $phoneNumber  = null)
    {
        $this->companyCode = $companyCode;
        $this->serviceCode = $serviceCode;
        $this->phoneNumber = $phoneNumber;
        $this->userId = env('PAYPOINT_USER_ID');
        $this->userPassword = env('PAYPOINT_USER_PASS');
        $this->salePointType = env('PAYPOINT_SALEPOINT_TYPE');
        $this->baseURI = env('PAYPOINT_BASE');
    }

    public function checkPayment($special = null, $special1 = null)
    {

        $transactionDate = date('Y-m-d\Th:i:s');
        $transactionId =  strtotime($transactionDate);
        $special = $special != null ? urlencode($special) : '';
        $special1 = $special1 != null ? urlencode($special1) : '';

        $url = $this->baseURI . "/CheckPayment";

        $params =
            'companyCode=' . urlencode($this->companyCode) .
            '&serviceCode=' . urlencode($this->serviceCode) .
            '&account=' . urlencode($this->phoneNumber) .
            '&special1=' . $special .
            '&special2=' . $special1 .
            '&transactionDate=' . urlencode($transactionDate) .
            '&transactionId=' . urlencode($transactionId) .
            '&userId=' . urlencode($this->userId) .
            '&userPassword=' . urlencode($this->userPassword) .
            '&salePointType=' . urlencode($this->salePointType);



        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));

        $data = curl_exec($ch);
        curl_close($ch);

        if ($data) {
            $response =  (new SimpleXMLElement($data));

            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            $responseCode = $array['@attributes']['Result'];

            try {
                $log = CheckPaymentLog::create([
                    'user_id' => auth()->guard('api')->id() ?? null,
                    'bill_no' => $array['BillInfo']['Bill']['BillNumber'],
                    'ref_stan' => $array['BillInfo']['Bill']['RefStan'],
                    'response' => json_encode($array),
                    'status' => $responseCode === "000",
                    'request' => $params
                ]);

                return $array['BillInfo']['Bill']['Amount'] >= "0"  ?  $array : $responseCode === "000";
            } catch (\Throwable $th) {

                $log = CheckPaymentLog::create([
                    'user_id' => auth()->guard('api')->id() ?? null,
                    'response' => json_encode($array),
                    'status' => $responseCode === "000",
                    'request' => $params
                ]);

                return $responseCode === "000";
            }
        } else
            return false;
    }

    public function executePayment($amount, $special = null, $special1 = null)
    {

        $transactionDate = date('Y-m-d\Th:i:s');
        $transactionId =  strtotime($transactionDate) . rand(0, 111);
        $special = $special != null ? urlencode($special) : '';
        $special1 = $special1 != null ? urlencode($special1) : '';

        if (auth()->guard('api')->id()) {
            $recentLog = CheckPaymentLog::where('user_id', auth()->guard('api')->id())->latest()->first();
        } else {
            $recentLog = CheckPaymentLog::latest()->first();
        }

        $billNumber = $recentLog->bill_no;
        $refStan = $recentLog->ref_stan;

        $url = $this->baseURI . "/ExecutePayment";


        $params =
            'companyCode=' . urlencode($this->companyCode) .
            '&serviceCode=' . urlencode($this->serviceCode) .
            '&account=' . urlencode($this->phoneNumber) .
            '&special1=' . $special .
            '&special2=' . $special1 .
            '&transactionDate=' . urlencode($transactionDate) .
            '&transactionId=' . urlencode($transactionId) .
            '&refStan=' . urlencode($refStan) .
            '&amount=' . urlencode($amount * 100) .
            '&billNumber=' . urlencode($billNumber) .
            '&userId=' . urlencode($this->userId) .
            '&userPassword=' . urlencode($this->userPassword) .
            '&salePointType=' . urlencode($this->salePointType);


        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));

        $data = curl_exec($ch);
        curl_close($ch);

        if ($data) {
            $response =  (new SimpleXMLElement($data));

            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            $responseCode = $array['@attributes']['Result'];

            $log = ExecutePaymentLog::create([
                'user_id' => auth()->guard('api')->id() ?? null,
                'key' => $array['@attributes']['Key'],
                'response' => json_encode($array),
                'status' => $responseCode === "000",
                'request' => $params
            ]);
            return $responseCode === "000";
        } else
            return false;
    }

    public function companyPackageInfo($serviceCode = null, $companyCode = null)
    {


        $url = $this->baseURI . "/GetCompanyPackagesInfo";


        $params =
            'companyCode=' . urlencode($companyCode ? $companyCode : $this->companyCode) .
            '&serviceCode=' . urlencode($serviceCode ? $serviceCode : $this->serviceCode) .
            '&userId=' . urlencode($this->userId) .
            '&userPassword=' . urlencode($this->userPassword) .
            '&salePointType=' . urlencode($this->salePointType);

        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));

        $data = curl_exec($ch);
        curl_close($ch);


        if ($data) {
            $response =  (new SimpleXMLElement($data));

            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            $responseCode = $array['@attributes']['Result'];

            return $responseCode === "000" ? $array['ResultData']['Info']['PackageInfoEx'] : null;
        } else
            return null;
    }

    public function companyInfo($serviceCode = null, $companyCode = null)
    {


        $url = $this->baseURI . "/GetCompanyInfo";


        $params =
            'companyCode=' . urlencode($companyCode ? $companyCode : $this->companyCode) .
            '&serviceCode=' . urlencode($serviceCode ? $serviceCode : $this->serviceCode) .
            '&userId=' . urlencode($this->userId) .
            '&userPassword=' . urlencode($this->userPassword) .
            '&salePointType=' . urlencode($this->salePointType);

        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml'));

        $data = curl_exec($ch);
        curl_close($ch);


        if ($data) {
            $response =  (new SimpleXMLElement($data));

            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);

            $responseCode = $array['@attributes']['Result'];

            return $responseCode === "000" ? $array['ResultData']['Info']['Location'] : null;
        } else
            return null;
    }
}
