<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

class RegisterRequest extends CommonRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'firstName' => 'bail|required|string|max:255',
			'lastName'  => 'bail|required|string|max:255',
			'countryCode'     => 'bail|required_if:from,normal|string|min:2',
			'phone'     => 'bail|required_if:from,normal|string|min:10|max:10|unique:users',
			'email'     => 'bail|nullable|email|unique:users',
			'from'      => 'bail|required|in:normal,facebook,apple,linkedin,google',
			'token'     => 'bail|required_unless:from,normal|string',
			'otp'     => 'bail|required_if:from,normal|string',
			'password'     => 'bail|required_if:from,normal|string|min:6',
			'address' => 'nullable|string',
			'officeAddress' => 'nullable|string',
			'lat' => 'nullable|string',
			'long' => 'nullable|string',
			'deviceToken' => 'nullable|string',
			'deviceType' => 'nullable|in:android,ios',
			'referCode' => 'nullable|string|max:10|min:5',
			'deviceId' => 'nullable|string',
			'district' => 'nullable|string',
			'municipality' => 'nullable|string',
			'ward' => 'nullable|string',
			'organization' => 'nullable|string',

		];
	}
}
