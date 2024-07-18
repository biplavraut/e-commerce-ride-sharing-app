<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteSliderRequest extends FormRequest
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
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|file|max:2048|mimes:png,jpg,jpeg',
            'slider_text' => 'bail|nullable|string',
            'hide' => 'bail|required|in:true,false,0,1',
        ];
    }
}
