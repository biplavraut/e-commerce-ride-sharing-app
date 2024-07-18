<?php

namespace App\Custom\Payment\Esewa;

use App\Custom\Payment\Validator;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class EsewaValidator extends Validator
{
	/**
	 * Check other parameters
	 *
	 * @throws Exception
	 */
	protected function checkOthers()
	{
		return $this->checkMerchantName();
	}

	/**
	 * Check if merchant name matches
	 *
	 * @return EsewaValidator
	 * @throws Exception
	 */
	private function checkMerchantName(): EsewaValidator
	{
		if ($this->response->merchantName() !== config('services.esewa.merchant_name')) {
			throw new Exception('Invalid merchant name.', Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}
}