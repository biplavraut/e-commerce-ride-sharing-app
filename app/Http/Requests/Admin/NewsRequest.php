<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|image:2048|dimensions:max_width=1300|mimes:png,jpg,jpeg',
            'name'        => 'bail|required|string|max:255|unique:news,name,' . $modelId,
            'slug'        => 'bail|required|string|max:255|unique:news,slug,' . $modelId,
            'description' => 'bail|required|string',
        ];
    }

    public function messages()
    {
        return [
            'image.dimensions' => 'Image width cannot be larger than 1300px',
        ];
    }
}
