<?php

namespace App\Custom\Payment\Contracts;

use Exception;

interface GatewayContract
{
	/**
	 * Verify the payment
	 *
	 * @param string $token
	 * @param int $amount
	 *
	 * @throws Exception
	 */
	public function verify($token, $amount = 0);

	/**
	 * Check if the payment is verified
	 *
	 * @return bool
	 */
	public function isVerified(): bool;

	/**
	 * Return the verification response array
	 *
	 * @return array
	 */
	public function response(): array;
}
