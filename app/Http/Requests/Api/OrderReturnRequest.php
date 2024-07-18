<?php

namespace App\Http\Requests\Api;

class OrderReturnRequest extends FormRequest
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
            'orderItemId' => 'required',
            'reason' => 'required|string|min:10|max:250',
            'quantity' => 'required|min:1'
        ];
    }
}
