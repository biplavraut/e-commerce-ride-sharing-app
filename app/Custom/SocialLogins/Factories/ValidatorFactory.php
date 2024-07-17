<?php

namespace App\Custom\SocialLogins\Factories;

use App\Custom\SocialLogins\Contracts\SocialResponse;
use App\Custom\SocialLogins\Abstracts\SocialValidator;

class ValidatorFactory
{
	/**
	 * Returns new instance of social validator
	 *
	 * @param string $name
	 * @param SocialResponse $response
	 *
	 * @param $options
	 *
	 * @return SocialValidator
	 * @throws \Exception
	 */
	public static function make(string $name, SocialResponse $response, $options = []): SocialValidator
	{
		$name = ucfirst($name);

		$className = "\\App\\Custom\\SocialLogins\\{$name}\\{$name}Validator";

		if (!class_exists($className)) {
			throw new \Exception("Class: {$className}, does not exist.");
		}

		return new $className($response, $options);
	}
}