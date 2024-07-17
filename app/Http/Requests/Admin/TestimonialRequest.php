<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
		$modelId = $this->route('testimonial');

		return [
			'name'    => 'bail|required|string|max:255',
			'image'   => 'bail|' . ($modelId ? 'nullable' : 'required') . '|image|max:2048|dimensions:max_width=1000|mimes:png,jpg,jpeg',
			'message' => 'bail|required|string',
		];
	}

	public function messages()
	{
		return [
			'image.dimensions' => 'Image width cannot be larger than 1000px',
		];
	}
}
