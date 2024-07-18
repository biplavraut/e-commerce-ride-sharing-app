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
            'title'   => 'bail|required|string|max:255',
            'slug'    => 'bail|required|string|max:255',
            'layout' => 'bail|nullable|string',
            'title_color' => 'bail|nullable|string',
            'layout_color' => 'bail|nullable|string',
            'service_id' => 'bail|required',
        ];
    }

    public function messages()
    {
        return [];
    }
}
