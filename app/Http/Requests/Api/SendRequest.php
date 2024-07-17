<?php

namespace App\Http\Requests\Api;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class SendRequest extends FormRequest
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
            'user_id' => 'integer|min:1',
            'pickup_location_name' => 'required|string|min:3',
            'delivery_location_name' => 'required|string|min:3',
            'pickup_location_lat' => 'required',
            'pickup_location_long' => 'required',
            'delivery_destination_lat' => 'required',
            'delivery_destination_long' => 'required',
            'distance_in_km' => 'required|min:1',
            'delivery_item_type' => 'exists:send_items,id',
            'delivery_item_weight' => 'required|min:1',
            'moneytery_value_of_item' => '',
            'generated_total_price' => '',
            'discount_price' => '',
            'discount_method' => '',
            'net_total_price' => '',
            'pickup_person_name' => 'required',
            'pickup_point_address' => 'required',
            'pickup_person_number' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'pickup_comment' => 'required',
            'contact_person_name' => 'required',
            'contact_person_address' => 'required',
            'contact_person_number' => 'required',
            'delivery_date' => 'required',
            'delivery_time' => 'required',
            'delivery_comment' => 'required',
            'notify_receipents_by_sms' => 'required',
            'status' => 'interger',
            'extra_column' => '',
            'confirm_order' => 'required|boolean',
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
