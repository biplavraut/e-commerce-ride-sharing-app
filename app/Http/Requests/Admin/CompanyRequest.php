<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
		return [
			'name'             => 'bail|required|string|max:255',
			'email'            => 'bail|required|email',
			'logo'             => 'bail|nullable|image|max:2048|dimensions:max_width=1000|mimes:png,jpg,jpeg',
			'established_date' => 'bail|nullable|string',
			'address'          => 'bail|required|string|max:255',
			'phone'            => 'bail|required|string|max:255',
			'about'            => 'bail|required|string',
		];
	}

	public function messages()
	{
		return [
			'logo.dimensions' => 'Logo width cannot be larger than 1000px',
		];
	}
}
