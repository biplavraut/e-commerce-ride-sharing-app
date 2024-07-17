<?php

namespace App\Custom\Payment;

use App\Custom\Payment\Contracts\GatewayContract;
use App\Custom\Payment\Contracts\ResponseContract;

class Factory
{
	/**
	 * Generate respective payment type class from the given name
	 *
	 * @param String $name
	 *
	 * @return GatewayContract
	 * @throws \Exception
	 */
	public static function make($name): GatewayContract
	{
		$name = ucfirst($name);

		$className = "\\App\\Custom\\Payment\\{$name}\\{$name}";

		if (!class_exists($className)) {
			throw new \Exception("Class: {$className}, does not exist.");
		}

		return new $className;
	}

	/**
	 * Generate respective payment type class from the given name
	 *
	 * @param string $name
	 *
	 * @param $token
	 * @param $amount
	 *
	 * @return ResponseContract
	 * @throws \Exception
	 */
	public static function makeResponse($name, $token, $amount): ResponseContract
	{
		$name = ucfirst($name);

		$className = "\\App\\Custom\\Payment\\{$name}\\{$name}Response";

		if (!class_exists($className)) {
			throw new \Exception("Class: {$className}, does not exist.");
		}

		if ($name === 'Khalti') {
			return new $className($token, $amount);
		}

		return new $className($token);
	}

	/**
	 * Generate respective payment type class from the given name
	 *
	 * @param String $name
	 *
	 * @param $response
	 * @param $params
	 *
	 * @return Validator
	 * @throws \Exception
	 */
	public static function makeValidator($name, $response, $params): Validator
	{
		$name = ucfirst($name);

		$className = "\\App\\Custom\\Payment\\{$name}\\{$name}Validator";

		if (!class_exists($className)) {
			throw new \Exception("Class: {$className}, does not exist.");
		}

		return new $className($response, $params);
	}
}