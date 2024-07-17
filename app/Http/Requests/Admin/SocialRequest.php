<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SocialRequest extends FormRequest
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
		$modelId = last(request()->segments());

		return [
			'name' => 'required|string|max:255',
			'icon' => ($modelId ? 'nullable' : 'required') . '|image|max:1024|dimensions:max_width=200|mimes:png,jpg,jpeg',
			'url'  => 'required|url',
		];
	}

	public function messages()
	{
		return [
			'icon.dimensions' => 'Icon width cannot be larger than 200px',
		];
	}
}
