<?php

namespace App\Custom\SocialLogins\Google;

use App\Custom\SocialLogins\Abstracts\SocialResponse;
use App\Custom\SocialLogins\Contracts\SocialResponse as SocialResponseContract;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GoogleResponse extends SocialResponse implements SocialResponseContract
{
	/**
	 * Call social login api for logging in user
	 *
	 * @return array
	 * @throws Exception
	 */
	public function execute(): array
	{
		$accessToken = explode('.', $this->token)[1];
		$userData    = base64_decode($accessToken, true);
		$data        = json_decode($userData, true);

		if (!$data) {
			throw new Exception("Invalid token passed.", Response::HTTP_BAD_REQUEST);
		}

		return json_decode($userData, true);
	}

	/**
	 * Returns user's email from the response
	 *
	 * @return string
	 */
	public function email(): string
	{
		return $this->data['email'];
	}

	/**
	 * Returns user's id from the response
	 *
	 * @return string
	 */
	public function id(): string
	{
		return $this->data['sub'];
	}

	/**
	 * Returns user's full name from the response
	 *
	 * @return string
	 */
	public function fullName(): string
	{
		return $this->data['given_name'] . ' ' . $this->data['family_name'];
	}

	/**
	 * Returns client id from the response
	 *
	 * @return string
	 */
	public function clientId(): string
	{
		return $this->data['aud'];
	}
}