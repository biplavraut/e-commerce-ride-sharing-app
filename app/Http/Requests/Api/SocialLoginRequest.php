<?php

namespace App\Http\Requests\Api;

class SocialLoginRequest extends CommonRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name'     => 'bail|required|string|max:191',
			'email'    => 'bail|required|email|max:191',
			'socialId' => 'bail|required|string',
			'from'     => ['bail', 'required', 'max:8', 'in:google,facebook,normal'],
			'token'    => 'bail|required|string',
		];
	}

	/**
	 * @return array
	 */
	public function messages()
	{
		return [
			'from.in' => 'The from field may be either google or facebook only.',
		];
	}
}
