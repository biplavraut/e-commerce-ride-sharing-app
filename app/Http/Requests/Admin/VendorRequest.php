<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'image' => 'bail|nullable|max:5000|mimes:jpeg,jpg,png,JPG',
            'business_name' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|string|email|unique:vendors,email,' . $modelId,
            'phone' => 'nullable|string|min:9:max:15|unique:vendors,phone,' . $modelId,
            'password' => 'bail|' . ($modelId ? 'nullable' : 'required') . '|string|min:6',
            'type' => 'nullable|string',
            'verified' => 'bail|' . ($modelId ? 'nullable' : 'required'),
            'city' => 'nullable|string',
            'address' => 'nullable|string',
            'area' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'radius_limit' => 'nullable|string',
            'is_hidden' => 'nullable|string|in:true,false,0,1',
            'order_offer_applicable' => 'nullable|string|in:true,false,0,1',
            'status' => 'nullable|string|in:true,false,0,1',
            'service_id' => 'nullable',
            'settlement_time' => 'nullable|string',
            'opening_time_form' => 'nullable|array',
            'vendor_option_categories' =>  'nullable|array',
            'takeaway' => 'bail|required|in:true,false,0,1',
            'dine_in' => 'bail|required|in:true,false,0,1',
        ];
    }
}
