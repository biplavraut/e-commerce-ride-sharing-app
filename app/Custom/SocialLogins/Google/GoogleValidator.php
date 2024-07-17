<?php

namespace App\Custom\SocialLogins\Google;

use App\Custom\SocialLogins\Abstracts\SocialValidator;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GoogleValidator extends SocialValidator
{
	/**
	 * Check other parameters
	 *
	 * @throws Exception
	 */
	protected function checkOthers()
	{
		$this->checkClientIdAndroid()
		     ->checkClientIdIOS();
	}

	/**
	 * Check android app id
	 *
	 * @return GoogleValidator
	 * @throws Exception
	 */
	private function checkClientIdAndroid(): GoogleValidator
	{
		if ($this->response->clientId() !== config('services.google.android.client_id')) {
			throw new Exception('Invalid Android Client ID.', Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}

	/**
	 * Check ios app id
	 *
	 * @return GoogleValidator
	 * @throws Exception
	 */
	private function checkClientIdIOS(): GoogleValidator
	{
		if ($this->response->clientId() !== config('services.google.ios.client_id')) {
			throw new Exception('Invalid iOS Client ID.', Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}
}