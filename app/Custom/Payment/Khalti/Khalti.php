<?php

namespace App\Custom\Payment\Khalti;

use App\Custom\Payment\Gateway;
use App\Custom\Payment\Contracts\GatewayContract;

class Khalti extends Gateway implements GatewayContract
{
	/**
	 * Returns base name of class
	 *
	 * @return string
	 */
	public function getClassBasename(): string
	{
		return "Khalti";
	}
}
