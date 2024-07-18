<?php

namespace App\Http\Requests\Api;

class LoginRequest extends FormRequest
{
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
        return [
            'email' => 'required_without:countryCode,phone|string',
            'countryCode' => 'required_without:email|string',
            'phone' => 'required_without:email|string',
            'password' => 'required|string|min:6',
            'deviceToken' => 'nullable|string',
            'deviceType' => 'nullable|in:android,ios',
        ];
    }
}
