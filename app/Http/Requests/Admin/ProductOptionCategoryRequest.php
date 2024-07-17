<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductOptionCategoryRequest extends FormRequest
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
            'title'        => 'bail|required|string|max:255|unique:product_option_categories,title,' . $modelId,
            'slug'        => 'bail|required|string|max:255|unique:product_option_categories,slug,' . $modelId,
            'layout' => 'bail|nullable|string',
        ];
    }

    public function messages()
    {
        return [];
    }
}
