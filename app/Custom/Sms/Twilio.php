<?php
namespace App\Custom\Sms;

use Twilio\Rest\Client;

class Twilio
{
    public $phone;
    public $message;

    public function __construct(string $phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function sendMessage()
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            $this->phone,
            ['from' => $twilio_number, 'body' => $this->message]
        );
        return $client;
    }
}
