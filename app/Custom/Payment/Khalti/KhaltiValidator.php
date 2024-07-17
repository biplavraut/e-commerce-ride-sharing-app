<?php

namespace App\Custom\Payment\Khalti;

use App\Custom\Payment\Validator;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class KhaltiValidator extends Validator
{
	/**
	 * Check other parameters
	 *
	 * @return KhaltiValidator
	 * @throws Exception
	 */
	protected function checkOthers(): KhaltiValidator
	{
		return $this->checkMerchantId();
	}

	/**
	 * Check if merchant is our own
	 *
	 * @return KhaltiValidator
	 * @throws Exception
	 */
	private function checkMerchantId(): KhaltiValidator
	{
		if ($this->response->merchantId() !== config('services.khalti.merchant_id')) {
			throw new Exception('Invalid merchant id.', Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}
}
