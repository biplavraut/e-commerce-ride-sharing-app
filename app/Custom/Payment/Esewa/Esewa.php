<?php

namespace App\Custom\Payment\Esewa;

use App\Custom\Payment\Contracts\GatewayContract;
use App\Custom\Payment\Gateway;

class Esewa extends Gateway implements GatewayContract
{
	/**
	 * Returns base name of class
	 *
	 * @return string
	 */
	public function getClassBasename(): string
	{
		return "Esewa";
	}
}