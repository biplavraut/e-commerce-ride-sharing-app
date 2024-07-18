<?php

namespace App\Custom\SocialLogins\Contracts;

interface SocialResponse
{
	/**
	 * Returns user's email from the response
	 *
	 * @return string
	 */
	public function email(): string;

	/**
	 * Returns user's id from the response
	 *
	 * @return string
	 */
	public function id(): string;

	/**
	 * Returns user's full name from the response
	 *
	 * @return string
	 */
	public function fullName(): string;

	/**
	 * Returns response data
	 *
	 * @return array
	 */
	public function getData(): array;
}