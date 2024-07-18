<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RidingFareRequest extends FormRequest
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
        $fareId = last(request()->segments());

        return [
            'vehicle' => 'bail|string|unique:riding_fares,vehicle,' . $fareId,
            'price' => 'required|integer',
            'flat_price' => 'nullable|integer',
            'night_surge' => 'nullable',
            'description' => 'nullable|string',
            'surges' => 'nullable|array',
            'surges.*.title' => 'nullable|string|max:100',
            'surges.*.from' => 'required|string',
            'surges.*.to' => 'required|string',
            'surges.*.price' => 'required|numeric|min:1',
        ];
    }
}
