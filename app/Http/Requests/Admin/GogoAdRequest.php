<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GogoAdRequest extends FormRequest
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
            'title'        => 'bail|required|string|max:255',
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|file:2048|mimes:png,jpg,jpeg',
            'url' => 'bail|nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'image.dimensions' => 'Image width cannot be larger than 1300px',
        ];
    }
}
