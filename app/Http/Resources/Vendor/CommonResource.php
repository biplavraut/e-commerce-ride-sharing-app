<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class CommonResource extends JsonResource
{
	protected $keyCamelCased = true;

	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return $this->nullToEmptyStringOfArray($request);
	}

	/**
	 * Transform the resource into an array by changing null values to empty string.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	abstract protected function toArrayWithoutNullValues($request);

	/**
	 * Dont convert the null value of the array with these keys to empty string
	 *
	 * @return array
	 */
	protected function ignoreNullValueOfKeys(): array
	{
		return [];
	}

	/**
	 * Converts null values of the array to empty string
	 *
	 * @param $request
	 *
	 * @return array
	 */
	private function nullToEmptyStringOfArray($request): array
	{
		$returnArray = nullToEmptyStringOfArray(
			$this->toArrayWithoutNullValues($request),
			$this->ignoreNullValueOfKeys()
		);

		if ($this->keyCamelCased) {
			return makeKeyCamelCased($returnArray);
		}

		return $returnArray;
	}
}
