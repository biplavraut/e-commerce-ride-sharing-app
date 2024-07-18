<?php

namespace App\Custom\Payment\Paypal;

use App\Custom\Payment\Validator;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PaypalValidator extends Validator
{
	/**
	 * Check other parameters
	 *
	 * @return PaypalValidator
	 * @throws Exception
	 */
	protected function checkOthers(): PaypalValidator
	{
		return $this->checkCurrency();
	}

	/**
	 * Check if the currency is as we require
	 *
	 * @return KhaltiValidator
	 * @throws Exception
	 */
	private function checkCurrency(): PaypalValidator
	{
		if ($this->response->currency() !== 'USD') {
			throw new Exception('Invalid Currency. Must be USD.', Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}
}
