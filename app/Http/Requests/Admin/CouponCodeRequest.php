<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponCodeRequest extends FormRequest
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
			'code'       => 'bail|required|string|max:255|unique:coupon_codes,code,' . $modelId,
			'amount' => 'bail|required|string',
			'valid_till' => 'bail|required|string'
		];
	}
}
