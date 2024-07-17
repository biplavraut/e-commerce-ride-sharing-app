<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RentalPackageRequest extends FormRequest
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
        $packageId = last(request()->segments());
        
        return [
            'name' => 'bail|string|unique:rental_packages,name,' . $packageId,
            'duration' => 'required|string',
            'distance' => 'required|string',
            'vehicles' => 'required|array',
            'vehicles.*.name' => 'required|string',
            'vehicles.*.price' => 'required|integer',
            'vehicles.*.description' => 'nullable|string',
            'description' => 'nullable|string'
        ];
    }
}
