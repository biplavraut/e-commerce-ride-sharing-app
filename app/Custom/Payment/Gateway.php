<?php

namespace App\Custom\Payment;

use App\Custom\Payment\Contracts\ResponseContract;
use Exception;

abstract class Gateway
{
	/**
	 * @var bool $verified
	 */
	protected $verified = false;
	/**
	 * @var ResponseContract $response
	 */
	protected $response;
	/**
	 * @var Validator $validator
	 */
	protected $validator;

	/**
	 * Returns base name of class
	 *
	 * @return string
	 */
	abstract public function getClassBasename(): string;

	/**
	 * Verify the payment
	 *
	 * @param string $token
	 * @param $amount
	 *
	 * @throws Exception
	 */
	public function verify($token, $amount = 0)
	{
		$className = $this->getClassBasename();

		$this->response  = Factory::makeResponse($className, $token, $amount);
		$this->validator = Factory::makeValidator($className, $this->response, ['amount' => $amount]);

		$this->validator->validate();
	}

	/**
	 * Checks if every validation has successfully passed
	 *
	 * @return bool
	 */
	public function isVerified(): bool
	{
		return $this->validator->isValidated();
	}

	/**
	 * Returns the response from the payment api response
	 *
	 * @return array
	 */
	public function response(): array
	{
		return $this->response->rawData();
	}
}
