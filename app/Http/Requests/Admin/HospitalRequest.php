<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
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
        $hospitalId = last(request()->segments());

        return [
            'title' => 'bail|required|string|max:150|unique:hospitals,title,' . $hospitalId,
            'lat' => 'bail|nullable|string',
            'long' => 'bail|nullable|string',
            'vendors' => 'nullable|array'
        ];
    }
}
