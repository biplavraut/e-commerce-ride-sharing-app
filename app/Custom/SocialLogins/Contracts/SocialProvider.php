<?php

namespace App\Custom\SocialLogins\Contracts;

interface SocialProvider
{
	/**
	 * Verifies the social login
	 *
	 * @throws \Exception
	 */
	public function verify();

	/**
	 * Returns user's email from the response
	 *
	 * @return string
	 */
	public function getEmail(): string;

	/**
	 * Returns user's id from the response
	 *
	 * @return string
	 */
	public function getId(): string;

	/**
	 * Returns user's full name from the response
	 *
	 * @return string
	 */
	public function getFullName(): string;

	/**
	 * Returns response data
	 *
	 * @return array
	 */
	public function getResponseData(): array;
}