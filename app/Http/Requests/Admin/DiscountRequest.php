<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
        return [
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|integer',
            'applied_from' => 'required|date|after:today',
            'applied_till' => 'required|date|after:applied_from',
            'status' => 'required|in:true,false',
        ];
    }
}
