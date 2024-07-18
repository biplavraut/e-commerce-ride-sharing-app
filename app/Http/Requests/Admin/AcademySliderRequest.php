<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AcademySliderRequest extends FormRequest
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
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|max:2048|mimes:png,jpg,jpeg',
            'name' => 'bail|required|string',
            'url' => 'bail|nullable|string',
            'fors' => 'bail|required|in:user,rider,vendor',
        ];
    }
}
