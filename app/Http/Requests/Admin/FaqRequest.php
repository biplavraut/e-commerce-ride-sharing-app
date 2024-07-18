<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'faq_title'        => 'bail|required|string|max:255|unique:faqs,faq_title,' . $modelId,
            'faq_description' => 'bail|required|string',
            'order'        => 'bail|required|numeric|max:50|unique:faqs,order,' . $modelId,
        ];
    }
}
