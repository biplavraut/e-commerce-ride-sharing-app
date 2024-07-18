<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GlobalNotificationRequest extends FormRequest
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
            'image' => 'bail|nullable|file|mimes:png,jpg,jpeg',
            'title' => 'bail|required|string|max:255',
            'for' => 'bail|nullable|string',
            'message' => 'bail|required|string',
            'geo' => 'bail|required|in:true,false,0,1',
            'sms' => 'bail|required|in:true,false,0,1',
            'lat' => 'required_if:geo,true',
            'long' => 'required_if:geo,true',
            'radius' => 'required_if:geo,true',
        ];
    }
}
