<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryJunctionRequest extends FormRequest
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
        $placeId = last(request()->segments());

        return [
            'location' => 'bail|required|string|max:150|unique:delivery_junctions,location,' . $placeId,
            'lat' => 'bail|nullable|string',
            'long' => 'bail|nullable|string',
        ];
    }
}
