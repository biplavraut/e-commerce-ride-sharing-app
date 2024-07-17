<?php

namespace App\Http\Resources\Admin;

class CompanyResource extends CommonResource
{
	/**
	 * Transform the resource into an array by changing null values to empty string.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArrayWithoutNullValues($request)
	{
		return [
			'name'             => $this->name,
			'email'            => $this->email,
			'phone'            => $this->phone,
			'established_date' => optional($this->established_date)->toDateString(),
			'address'          => $this->address,
			'about'            => $this->about,
			'logo'             => $this->logo,
		];
	}
}
