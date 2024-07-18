<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SuperUserRequest extends FormRequest
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
            'name' => 'required|string',
            'type' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|string|email|unique:admins,email,' . $modelId
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Please select proper role.',
        ];
    }
}
