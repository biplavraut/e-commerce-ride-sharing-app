<?php

namespace App\Http\Requests\Api;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class Uservehiclerequest extends FormRequest
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
            'vehicle_color' => 'required|string',
            'main_type' => 'required|in:Bike,Car,Scooter,Moped',
            'type'     => 'required|in:Premium,SUV,Sedan,Hatch Back',
            'reg_no'   => 'required',
            'fuel_sharing_km'  =>  'required|integer',
            'vehicle_image'  =>  'required|mimes:jpg,jpeg,png', 
            'license_image'  =>  'required|mimes:jpg,jpeg,png', 
            'offering_seats'  =>  'required|integer',
            'check_point' => 'nullable|string',
            'features' => 'nullable|string',
            'remarks' => 'nullable|string',
            'is_default' => 'bail|sometimes|bool'
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
