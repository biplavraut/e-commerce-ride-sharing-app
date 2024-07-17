<?php

namespace App\Custom\SocialLogins\Factories;

use App\Custom\SocialLogins\Contracts\SocialProvider;

class SocialProviderFactory
{
	/**
	 * Returns new instance of social response
	 *
	 * @param $name
	 * @param $token
	 * @param $email
	 *
	 * @return SocialProvider
	 * @throws \Exception
	 */
	public static function make($name, $token = null, $email = ''): SocialProvider
	{
		$name = ucfirst($name);

		$className = "\\App\\Custom\\SocialLogins\\{$name}\\{$name}";

		if (!class_exists($className)) {
			throw new \Exception("Class: {$className}, does not exist.");
		}

		return new $className($token, $email);
	}
}