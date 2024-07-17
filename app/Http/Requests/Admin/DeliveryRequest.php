<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryRequest extends FormRequest
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
            'order_id' => 'required|string',
            'driver_id' => 'nullable|string',
            'from' => 'required|string',
            'to' => 'required|string',
            'from_lat' => 'required|string|max:155',
            'from_long' => 'required|string|max:155',
            'to_lat' => 'required|string|max:155',
            'to_long' => 'required|string|max:155',
            'vehicle_type' => 'required|string|max:155',
        ];
    }
}
