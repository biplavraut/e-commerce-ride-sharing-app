<?php

namespace App\Custom\SocialLogins\Abstracts;

use App\Custom\SocialLogins\Factories\ResponseFactory;
use App\Custom\SocialLogins\Factories\ValidatorFactory;

abstract class SocialProvider
{
	/**
	 * @var string
	 */
	protected $token;
	/**
	 * @var string
	 */
	protected $email;
	/**
	 * @var SocialResponse
	 */
	protected $response;
	/**
	 * @var SocialValidator
	 */
	protected $validator;

	public function __construct(string $token = null, string $email = '')
	{
		$this->token     = $token;
		$this->email     = $email;
		$this->response  = ResponseFactory::make($this->getClassBasename(), $token);
		$this->validator = ValidatorFactory::make($this->getClassBasename(), $this->response, ['email' => $email]);
	}

	/**
	 * Returns the class name
	 *
	 * @return string
	 */
	abstract protected function getClassBasename(): string;

	/**
	 * Verifies the social login
	 *
	 * @throws \Exception
	 */
	public function verify()
	{
		$this->validator->validate();
	}

	/**
	 * Returns user's email from the response
	 *
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->response->email();
	}

	/**
	 * Returns user's id from the response
	 *
	 * @return string
	 */
	public function getId(): string
	{
		return $this->response->id();
	}

	/**
	 * Returns user's full name from the response
	 *
	 * @return string
	 */
	public function getFullName(): string
	{
		return $this->response->fullName();
	}

	/**
	 * Returns response data
	 *
	 * @return array
	 */
	public function getResponseData(): array
	{
		return $this->response->getData();
	}
}