<?php

namespace App\Custom\Payment\Contracts;

use Carbon\Carbon;

interface ResponseContract
{
	/**
	 * Must return 'approved' if the payment has been successfully completed.
	 *
	 * @return string
	 */
	public function status(): string;

	/**
	 * Returns the total amount in the type (Rs|Paisa|$|Cents) as required by the payment provider
	 *
	 * @return mixed
	 */
	public function totalAmount();

	/**
	 * Returns the full response array from the verification api
	 *
	 * @return array
	 */
	public function rawData(): array;

	/**
	 * Returns the Carbon instance of the payment creation date (i.e. paid date)
	 *
	 * @return Carbon
	 */
	public function createdAt(): Carbon;
}
