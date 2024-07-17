<?php

namespace App\Custom\SocialLogins\Normal;

use App\Custom\SocialLogins\Abstracts\SocialProvider;
use App\Custom\SocialLogins\Contracts\SocialProvider as SocialProviderContract;

class Normal extends SocialProvider implements SocialProviderContract
{
	/**
	 * Returns the class name
	 *
	 * @return string
	 */
	protected function getClassBasename(): string
	{
		return "Normal";
	}
}