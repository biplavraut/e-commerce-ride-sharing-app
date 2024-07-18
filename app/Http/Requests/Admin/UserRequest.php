<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$userId = $this->route('user');

		return [
			'name'                  => 'bail|required|string|max:255',
			'email'                 => [
				'bail',
				'required',
				'email',
				'max:255',
				Rule::unique('users')->ignore($userId),
			],
			'image'                 => 'bail|nullable|image|max:2048|dimensions:max_width=1000|mimes:png,jpg,jpeg',
			'password'              => 'bail|string|same:password_confirmation' . $userId ? 'nullable' : 'required',
			'password_confirmation' => 'bail|string|' . $userId ? 'nullable' : 'required',
			'address'               => 'bail|string|nullable',
			'dob'                   => 'bail|date|nullable',
		];
	}

	public function messages()
	{
		return [
			'image.dimensions' => 'Image width cannot be larger than 1000px',
		];
	}
}
