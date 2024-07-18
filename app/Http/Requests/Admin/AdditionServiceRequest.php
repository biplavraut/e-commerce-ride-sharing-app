<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdditionServiceRequest extends FormRequest
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
			'image'              => 'bail|nullable|file:image|max:2048|mimes:png,jpg,jpeg',
            'name'               => 'bail|required|string|max:255',
            'subtitle'           => 'bail|nullable|string|max:255',
            'slug'               => 'bail|required|string|max:255|unique:additional_services,slug,' . $modelId,
            'enabled' 		     => 'nullable|in:true,false,0,1',
            'enabled_promo'      => 'nullable|in:true,false,0,1',
            'cashback'           => 'nullable|max:100',
        ];
    }
}