<?php

namespace App\Helper;

class ResponseMessage
{
    const ERROR = 'An error has occurred please try again later.';

    const OrderSuccess  =   'Thank you for shopping at gogo20! Your order is being verified.';

    const PAYMENTFAILED  =   'An error has occurred while payment verification.';

    const PAYPOINT_SUCCESS  =   'Operation is succesfully completed.';

    const PAYPOINT_ERROR  =   'An error has occurred while performing an operation.';

    const OUTOFSTOCK = 'Product Out of Stock';
}
