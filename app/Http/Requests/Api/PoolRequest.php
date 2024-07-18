<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class PoolRequest extends FormRequest
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
            'current_location' => 'required|string|min:5',
            'desire_destination' => 'required|string|min:5',
            'location_lat'     => 'required|min:',
            'location_long'   => 'required',
            'destination_lat'  =>  'required',
            'destination_long'  =>  'required',
            'date'  =>  'required:date',
            'time' => 'required|date_format:H:i',
            'distance_in_km' => 'required',
            'required_seat' => 'required|min:1|max:5',
            'vechical_type' => 'required',
            'vehicle_id' => 'required_if:pool_type,offer',
            'is_recurring' => 'required_if:pool_type,offer|bool',
            'recurring_strat_date' => 'required_if:is_recurring,true',
            'recurring_end_date' => 'required_if:is_recurring,true',
            'pool_type' => 'required|in:offer,request',
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
