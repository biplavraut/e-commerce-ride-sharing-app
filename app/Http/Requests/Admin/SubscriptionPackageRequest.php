<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPackageRequest extends FormRequest
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
            'name' => 'bail|string', //unique:subscription_packages,name,' . $modelId
            'type' => 'bail|required|in:amount,percent',
            'duration' => 'bail|required|in:per-ride,monthly,yearly',
            'two_wheel_value' => 'required',
            'four_wheel_value' => 'required',
            'hide' => 'bail|required|in:true,false,0,1',
        ];
    }
}
