<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoadBlockNotificationRequest extends FormRequest
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
            'image' => 'bail|nullable|file|mimes:png,jpg,jpeg',
            'title' => 'bail|required|string|max:255|unique:road_block_messages,title,' . $modelId,
            'description' => 'bail|nullable|string',
            'show_image_on_top' => 'bail|required|in:true,false,0,1',
            'status' => 'bail|required|in:true,false,0,1',
        ];
    }
}
