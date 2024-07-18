<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
			'name' => 'bail|required|string',
			'winners' => 'bail|nullable|array',
			'prizes' => 'bail|nullable|array',
			'types' => 'bail|nullable|array',
			'held_on' => 'bail|nullable|string',
			'user_type' => 'bail|nullable|string',
			'description' => 'bail|nullable|string'
		];
	}
}
