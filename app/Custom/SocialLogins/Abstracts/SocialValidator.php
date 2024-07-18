<?php

namespace App\Custom\SocialLogins\Abstracts;

use Exception;
use App\Custom\SocialLogins\Contracts\SocialResponse as SocialResponseContract;
use Symfony\Component\HttpFoundation\Response;

abstract class SocialValidator
{
	/**
	 * @var SocialResponseContract
	 */
	protected $response;
	protected $email;

	public function __construct(SocialResponseContract $response, $options)
	{
		$this->response = $response;
		$this->email    = $options['email'] ?? '';
	}

	/**
	 * Check other parameters
	 *
	 * @throws Exception
	 */
	abstract protected function checkOthers();

	/**
	 * Validates all the response data
	 *
	 * @throws Exception
	 */
	final public function validate(): void
	{
		$this->checkEmail()
		     ->checkOthers();
	}

	/**
	 * Check if email matches
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function checkEmail(): SocialValidator
	{
		if ($this->emailIsPresent() && $this->email !== $this->response->email()) {
			throw new Exception("Invalid email.", Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}

	/**
	 * Checks if email is present
	 *
	 * @return bool
	 */
	private function emailIsPresent(): bool
	{
		return $this->email !== '' && $this->email !== null;
	}
}
