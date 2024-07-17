<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class VendorRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'businessName' => 'required|string',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|email|unique:vendors',
            'countryCode' => 'nullable|string|max:5',
            'phone' => 'required|string|min:9:max:10|unique:vendors',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'nullable|image',
            'partnershipType' => 'required|string',
            'type' => 'required|string',
            'heardFrom' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
        ];
    }

    
    protected function failedValidation(Validator $validator)
    {
        if ($validator->fails() && $this->expectsJson()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            throw new HttpResponseException(
                response()->json(['status' => false, 'message' => $errors, 'statusCode' => 422], 422)
            );
        }
        parent::failedValidation($validator);
    }
}
