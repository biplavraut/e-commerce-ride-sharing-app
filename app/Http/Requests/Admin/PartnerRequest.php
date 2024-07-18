<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            'name' => 'bail|required|string',
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|max:2048|mimes:png,jpg,jpeg',
            'expire_in' => 'bail|nullable',
            'hide' => 'bail|required|in:true,false,0,1',
            'vendor_id' => 'bail|nullable',
            'has_branches' => 'bail|nullable|in:true,false,0,1',
            'parent_id' => 'bail|nullable',
        ];
    }
}
