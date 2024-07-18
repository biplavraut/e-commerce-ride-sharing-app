<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PremiumPlaceRequest extends FormRequest
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
            'image' => 'bail|nullable|image:2048|mimes:png,jpg,jpeg',
            'location' => 'bail|required|string|max:150|unique:premium_places,location,' . $placeId,
            'lat' => 'bail|nullable|string',
            'long' => 'bail|nullable|string',
            'outstation_price' => 'bail|nullable|min:0',
            'price' => 'bail|nullable|min:1',
            'radius' => 'bail|nullable|integer',
            'popular' => 'bail|nullable|in:0,1,true,false',
            'hide' => 'bail|nullable|in:0,1,true,false'
        ];
    }
}
