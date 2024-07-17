<?php

namespace App\Custom\SocialLogins\Normal;

use App\Custom\SocialLogins\Abstracts\SocialResponse;
use App\Custom\SocialLogins\Contracts\SocialResponse as SocialResponseContract;

class NormalResponse extends SocialResponse implements SocialResponseContract
{
	/**
	 * Call social login api for logging in user
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function execute(): array
	{
		return [];
	}

	/**
	 * Returns user's email from the response
	 *
	 * @return string
	 */
	public function email(): string
	{
		return request()->input('email') ?? '';
	}

	/**
	 * Returns user's id from the response
	 *
	 * @return string
	 */
	public function id(): string
	{
		return '';
	}

	/**
	 * Returns user's full name from the response
	 *
	 * @return string
	 */
	public function fullName(): string
	{
		return '';
	}
}