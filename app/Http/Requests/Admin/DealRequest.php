<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
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
            'category_id' => 'bail|required|string',
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|max:2048|mimes:png,jpg,jpeg,webp',
            'title' => 'bail|required|string',
            'sub_title' => 'bail|nullable|string',
            'from' => 'bail|required|string',
            'to' => 'bail|required|string',
            'status' => 'bail|nullable|string',
            'bg_color' => 'bail|nullable|string',
            'text_color' => 'bail|nullable|string'            
        ];
    }
}
