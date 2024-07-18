<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = last(request()->segments());
        return [
            'product_category_id' => 'bail|nullable',
            'vendor_id' => 'bail|nullable',
            'title' => 'string',
            'code' => 'nullable|string|unique:products,code,' . $productId,
            'slug' => 'string|unique:products,slug,' . $productId,
            'badge' => 'nullable|array',
            'price' => 'required|integer',
            'elite_percent' => 'nullable|min:0|max:100',
            'price_1' => 'nullable',
            'opening_stock' => 'bail|nullable|integer',
            'description' => 'string|nullable',
            'discount_type' => 'bail|nullable|string',
            'discount' => 'bail|nullable|integer|' . ($this->discount_type == 'percent' ? 'max:100' : '') . '|min:0',
            'batch_no' => 'bail|nullable|string',
            'expire_date' => 'bail|nullable|string',
            'hide' => 'required|in:1,0,true,false',
            'is_default' => 'nullable|in:1,0,true,false',
            'unit' => 'bail|nullable|string',
            'images' => 'bail|nullable|array',
            'images.*' => 'bail|nullable|max:5000|mimes:jpeg,jpg,png,gif,JPG,webp',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|string|max:100',
            'size' => 'bail|nullable|array',
            'color' => 'bail|nullable|array',
            'product_option_categories' =>  'nullable|array',
            'vat_percentage' =>  'nullable|min:0|max:100',
            'service_charge_percentage' => 'nullable|min:0|max:100',
            'is_return' => 'nullable|in:1,0,true,false',
            'return_days' => 'nullable|string',
        ];
    }
}
