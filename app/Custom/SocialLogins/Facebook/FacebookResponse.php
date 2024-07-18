<?php

namespace App\Custom\SocialLogins\Facebook;

use App\Custom\HttpRequest;
use App\Custom\SocialLogins\Abstracts\SocialResponse;
use App\Custom\SocialLogins\Contracts\SocialResponse as SocialResponseContract;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class FacebookResponse extends SocialResponse implements SocialResponseContract
{
	const USER_DATA_URL = 'https://graph.facebook.com/v3.2/me';
	const APP_DATA_URL = 'https://graph.facebook.com/v3.2/app';

	private $httpRequest;
	private $userData = [];
	private $appData = [];

	public function __construct(string $token)
	{
		$this->httpRequest = new HttpRequest();
		parent::__construct($token);
	}

	/**
	 * Call social login api for logging in user
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function execute(): array
	{
		$userData       = $this->httpRequest->get(self::USER_DATA_URL, [
			'fields'       => 'id,name,email',
			'access_token' => $this->token,
		])->execute();
		$this->userData = $userData->response();
		if ($userData->statusCode() !== Response::HTTP_OK) {
			throw new Exception($this->userData['error']['message'], $userData->statusCode());
		}

		$appData       = $this->httpRequest->get(self::APP_DATA_URL, [
			'access_token' => $this->token,
		])->execute();
		$this->appData = $appData->response();
		if ($appData->statusCode() !== Response::HTTP_OK) {
			throw new Exception($this->appData['error']['message'], $appData->statusCode());
		}

		return [
			'user' => $this->userData,
			'app'  => $this->appData,
		];
	}

	/**
	 * Returns user's email from the response
	 *
	 * @return string
	 */
	public function email(): string
	{
		return $this->userData['email'];
	}

	/**
	 * Returns user's id from the response
	 *
	 * @return string
	 */
	public function id(): string
	{
		return $this->userData['id'];
	}

	/**
	 * Returns user's full name from the response
	 *
	 * @return string
	 */
	public function fullName(): string
	{
		return $this->userData['name'];
	}

	/**
	 * Returns app id from the response
	 *
	 * @return string
	 */
	public function appId(): string
	{
		return $this->appData['id'];
	}
}