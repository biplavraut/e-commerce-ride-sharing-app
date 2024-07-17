<?php

namespace App\Custom\Payment\Khalti;

use App\Custom\Payment\Contracts\ResponseContract;
use App\Custom\Payment\Response as PaymentResponse;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class KhaltiResponse extends PaymentResponse implements ResponseContract
{
    private $total;

    public function __construct(string $token, int $total)
    {
        $this->total = $total;
        parent::__construct($token);
        $this->paymentMode = config('services.khalti.mode');
    }

    /**
     *  Get secret key according to payment mode
     *
     * @return string
     */
    private function getSecretKey(): string
    {
        return "test_secret_key_8d5d601bd84f48aa80e74bd427f134d6";
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
        $args = http_build_query([
            'token' => $token,
            'amount' => $this->total,
        ]);

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://khalti.com/api/v2/payment/verify/');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key ' . $this->getSecretKey()];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            throw new Exception('Something went wrong. Please try again later.', $statusCode);
        }
        curl_close($ch);

        $result = json_decode($response, true);

        // if ($statusCode !== Response::HTTP_OK) {
        //     throw new Exception($this->errorMessage($result, $statusCode), $statusCode);
        // }

        return $result;
    }

    /**
     * Returns the error message on payment verification
     *
     * @param $error
     *
     * @param $statusCode
     *
     * @return string
     */

    private function errorMessage($error, $statusCode): string
    {
        if ($statusCode === Response::HTTP_UNAUTHORIZED) {
            return $error['detail'];
        }

        if ($statusCode === Response::HTTP_BAD_REQUEST) {
            switch ($error['error_key']) {
                case 'validation_error':
                    if (isset($error['token'])) {
                        return "token: {$error['token'][0]}";
                    } elseif (isset($error['amount'])) {
                        return "amount: {$error['amount'][0]}";
                    } else {
                        return 'validation_error: ' . json_encode($error);
                    }
                    break;
                case 'already_verified':
                    return $error['detail'];
                    break;
            }
        }

        return json_encode($error);
    }

    /**
     * Must return 'approved' if the payment has been successfully completed.
     *
     * @return string
     */

    public function status(): string
    {
        if (isset($this->response['state']['name'])) {
            return $this->response['state']['name'] === 'Completed' ? 'approved' : 'failure';
        }
        return "failure";
    }

    /**
     * Returns the total amount in the type ( Rs|Paisa|$|Cents ) as required by the payment provider
     *
     * @return mixed
     */

    public function totalAmount()
    {
        return $this->response['amount'];
    }

    /**
     * Returns the Carbon instance of the payment creation date ( i.e. paid date )
     *
     * @return Carbon
     */

    public function createdAt(): Carbon
    {
        return Carbon::parse($this->response['created_on']);
    }

    /**
     * Returns the merchant unique id
     *
     * @return string
     */

    public function merchantId(): string
    {
        return $this->response['merchant']['idx'];
    }

    /**
     * Returns the transaction unique id
     *
     * @return string
     */

    public function transId()
    {
        return $this->response['idx'];
    }
}
