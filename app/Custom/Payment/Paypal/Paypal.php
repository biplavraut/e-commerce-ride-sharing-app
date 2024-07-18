<?php

namespace App\Custom\Payment\Paypal;

use App\Custom\Payment\Gateway;
use App\Custom\Payment\Contracts\GatewayContract;

class Paypal extends Gateway implements GatewayContract
{
	/**
	 * Returns base name of class
	 *
	 * @return string
	 */
	public function getClassBasename(): string
	{
		return "Paypal";
	}
}
