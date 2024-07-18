<?php

namespace App\Custom\SocialLogins\Factories;

use App\Custom\SocialLogins\Abstracts\SocialResponse;

class ResponseFactory
{
	/**
	 * Returns new instance of social response
	 *
	 * @param $name
	 * @param $token
	 *
	 * @return SocialResponse
	 * @throws \Exception
	 */
	public static function make($name, $token): SocialResponse
	{
		$name = ucfirst($name);

		$className = "\\App\\Custom\\SocialLogins\\{$name}\\{$name}Response";

		if (!class_exists($className)) {
			throw new \Exception("Class: {$className}, does not exist.");
		}

		return new $className($token);
	}
}