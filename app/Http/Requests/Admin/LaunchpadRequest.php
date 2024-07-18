<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LaunchpadRequest extends FormRequest
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
            'launchpad_category_id' => 'bail|required|integer',
            'name' => 'bail|required|string',
            'image'       => 'bail|' . ($modelId ? 'nullable' : 'required') . '|max:2048|mimes:png,jpg,jpeg',
            'description' => 'bail|nullable|string',
            'url' => 'bail|nullable|string',
            'hide' => 'bail|required|in:true,false,0,1'
        ];
    }
}
