<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\FormRequest;

class DriverRegisterRequest extends FormRequest
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
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'dob' => 'nullable|string',
            'heardFrom' => 'nullable|string',
            'email' => 'nullable|string|email|unique:drivers,email',
            'countryCode' => 'required|string|max:5',
            'phone' => 'required|string|min:10:max:10|unique:drivers,phone',
            'password' => 'required|string|min:6',
            'image' => 'nullable|image',
            'address' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'deviceToken' => 'nullable|string',
            'deviceType' => 'nullable|in:android,ios',
            'referCode' => 'nullable|string|min:10|max:10',
            'subscription' => 'nullable|string',
            'subscriptionPackageId' => 'nullable|integer',
            'licenseNo' => 'nullable|string',
            'license' => 'nullable|file|mimes:jpg,jpeg,png,JPG',
            'plateNo' => 'nullable|string',
            'picture' => 'nullable|file|mimes:jpg,jpeg,png,JPG',
            'type' => 'nullable|string',

        ];
    }
}
