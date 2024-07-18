<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AcademyContentRequest extends FormRequest
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
            'image'       => 'nullable|max:2048|mimes:png,jpg,jpeg',
            'title' => 'bail|required|string',
            'url' => 'bail|nullable|string',
            'video_url' => 'bail|nullable|string',
            'fors' => 'bail|required|in:user,rider,vendor',
            'description' => 'bail|nullable|string',
        ];
    }
}
