<?php

namespace App\Custom\Payment;

use App\Custom\Payment\Contracts\ResponseContract;
use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class Validator
{
	protected $response;
	protected $amount;
	private $validated = false;

	/**
	 * Validator constructor.
	 *
	 * @param ResponseContract $response
	 * @param array            $params
	 */
	public function __construct(ResponseContract $response, $params)
	{
		$this->response = $response;
		$this->amount   = $params['amount'];
	}

	/**
	 * Check other parameters
	 *
	 * @throws Exception
	 */
	protected abstract function checkOthers();

	/**
	 * Validate the payment request
	 *
	 * @throws Exception
	 */
	final public function validate(): void
	{
		$this->checkIfApproved()
		     ->checkTotalAmount()
		     ->checkOthers();

		$this->validated = true;
	}

	/**
	 * Check if the payment is approved or not
	 *
	 * @return Validator
	 * @throws Exception
	 */
	private function checkIfApproved(): Validator
	{
		$status = $this->response->status();

		if ($status !== 'approved') {
			throw new Exception("The payment is not approved yet. Status: {$status}.", Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}

	/**
	 * Check if the total amount matches the server amount
	 *
	 * @return Validator
	 * @throws Exception
	 */
	private function checkTotalAmount(): Validator
	{
		if ((string)$this->response->totalAmount() !== (string)$this->amount) {
			throw new Exception('Invalid total amount.', Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}

	/**
	 * Checks if every validation has successfully passed
	 *
	 * @return bool
	 */
	public function isValidated()
	{
		return $this->validated;
	}
}
