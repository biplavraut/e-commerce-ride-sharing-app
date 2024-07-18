<?php

namespace App\Http\Requests\Api;

class SignupRequest extends CommonRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'     => 'bail|required|string',
			'email'    => 'bail|required|email|unique:users',
			'password' => 'bail|required|min:6|confirmed',
		];
	}
}
