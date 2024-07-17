<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'paymentType' => 'required|in:khalti,esewa,imepay,cod,gogoWallet|string',
            'address'       => 'required|string',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'shippingFee'   => 'required',
            'items.*.productId'  =>  'required|integer',
            'items.*.vendorId'  =>  'required|integer',
            'items.*.quantity'  =>  'required|integer',
            'token' => 'required_if:paymentType,esewa,imepay,khalti|string',
            'couponCode' => 'nullable|string',
            'donationTrust' => 'nullable',
            'donation' => 'nullable',
            'deliveryArea' => 'required|string',
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
