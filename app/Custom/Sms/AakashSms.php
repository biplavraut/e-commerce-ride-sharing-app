<?php

namespace App\Custom\Sms;

class AakashSms
{
    public $phone;
    public $message;
    public $token;

    public function __construct(string $token = null, string $phone, string $message)
    {
        $this->token = $token;
        $this->phone = $phone;
        $this->message = $message;
    }

    public function sendMessage()
    {
        $args = http_build_query(array(
            'auth_token' => env('SMS_TOKEN', 'cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824'),
            'to' => $this->phone,
            'text' => $this->message
        ));

        $url = "https://sms.aakashsms.com/sms/v3/send";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); //Using insecure mode

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = json_decode($response, true);

        return $result['error'];
    }
}
