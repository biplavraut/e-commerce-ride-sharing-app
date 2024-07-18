<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
            'patientName' => 'nullable|string',
            'patientAge' => 'nullable|string',
            'hospital' => 'required|string',
            'doctorName' => 'nullable|string',
            'doctorNmc' => 'nullable|string',
            'address'       => 'required|string',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'deliveryArea' => 'required|string',
            'nearestLandmark' => 'nullable|string',
            'preferredDate' => 'nullable|string',
            'preferredTime' => 'nullable|string',
            'alternateName' => 'nullable|string',
            'alternatePhone' => 'nullable|string',
            'additionalDetail' => 'nullable|string',
            'images' => 'bail|required|array',
            'images.*' => 'bail|required|max:5000|mimes:jpeg,jpg,png,gif,JPG',
        ];
    }
}
