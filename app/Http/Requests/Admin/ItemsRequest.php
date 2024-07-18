<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ItemsRequest extends FormRequest
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

        $modelId = last(request()->segments());
        return [
            'name' => 'required|string|max:255|unique:send_items,name,' . $modelId,
            'flat_price' => 'required|numeric',
            'status' => 'required',
            'added_per_km_price' => 'nullable|numeric',
            'added_weightprice_per_kg' => 'nullable|numeric',
        ];
    }
}
