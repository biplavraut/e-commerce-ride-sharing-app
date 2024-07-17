<?php

namespace App\Custom\SocialLogins\Abstracts;

abstract class SocialResponse
{
	/**
	 * @var string
	 */
	protected $token;
	/**
	 * @var array
	 */
	protected $data;

	public function __construct(string $token = null)
	{
		$this->token = $token;
		$this->data  = $this->execute();
	}

	/**
	 * Call social login api for logging in user
	 *
	 * @return array
	 * @throws \Exception
	 */
	abstract public function execute(): array;

	/**
	 * Get response data form the api
	 *
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}
}