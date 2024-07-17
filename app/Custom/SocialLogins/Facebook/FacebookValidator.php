<?php

namespace App\Custom\SocialLogins\Facebook;

use App\Custom\SocialLogins\Abstracts\SocialValidator;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class FacebookValidator extends SocialValidator
{
	/**
	 * Check other parameters
	 *
	 * @throws Exception
	 */
	protected function checkOthers()
	{
		$this->checkAppIdAndroid()
		     ->checkAppIdIOS();
	}

	/**
	 * Check android app id
	 *
	 * @return FacebookValidator
	 * @throws Exception
	 */
	private function checkAppIdAndroid(): FacebookValidator
	{
		if (config('services.facebook.android.app_id') !== $this->response->appId()) {
			throw new Exception("Invalid Android App ID.", Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}

	/**
	 * Check ios app id
	 *
	 * @return FacebookValidator
	 * @throws Exception
	 */
	private function checkAppIdIOS(): FacebookValidator
	{
		if (config('services.facebook.ios.app_id') !== $this->response->appId()) {
			throw new Exception("Invalid iOS App ID.", Response::HTTP_BAD_REQUEST);
		}

		return $this;
	}
}