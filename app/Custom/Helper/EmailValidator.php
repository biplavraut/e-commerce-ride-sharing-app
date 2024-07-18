<?php

namespace App\Custom\Helper;

class EmailValidator
{

    private $email;
    private $domain;

    /**
     * EmailValidator constructor.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    private function extractDomain()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            // split on @ and return last value of array (the domain)
            $tmpDom = explode('@', $this->email);
            $this->domain = end($tmpDom);
        }
    }

    public function validate()
    {
        $this->extractDomain();
        $ch = curl_init($this->domain);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($code == 200 || $code == 301) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
}
