<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $categoryId = last(request()->segments());

        $rules = [
            'image'              => 'bail|nullable|file:image|max:2048|dimensions:max_width=1000|mimes:png,jpg,jpeg',
            'name'               => 'bail|required|string|max:255',
            'subtitle'               => 'bail|nullable|string|max:255',
            'slug'               => 'bail|required|string|max:255|unique:categories,slug,' . $categoryId,
            'has_sub_categories' => 'bail|nullable|boolean',
            'enabled' =>            'nullable',
            'sub_images'         => 'bail|nullable|array',
            'sub_images.*'       => 'bail|nullable|image|max:2048|dimensions:max_width=1000|mimes:png,jpg,jpeg',
            'sub_names'          => 'bail|required_if:has_sub_categories,1|array',
            'sub_names.*'        => 'bail|required_if:has_sub_categories,1|string',
            'sub_slugs'          => 'bail|required_if:has_sub_categories,1|array',
            //'sub_slugs.*'        => 'bail|required_if:has_sub_categories,1|string|unique:categories,slug',
        ];

        // check if sub categories slugs are unique
        foreach (request()->input('sub_ids') ?? [] as $key => $subId) {
            $subCatId                  = $subId ?: null;
            $rules["sub_slugs.{$key}"] = "bail|required_if:has_sub_categories,1|string|unique:categories,slug,{$subCatId}";
        }

        return $rules;
    }

    public function messages()
    {
        $request  = request();
        $messages = [];

        $imageDimensionsMessage = 'Image width cannot be larger than 1000px';

        foreach ($request->input('sub_slugs') ?? [] as $key => $subSlug) {
            $messages["sub_slugs.{$key}.unique"]      = "The slug '{$subSlug}' has already been taken.";
            $messages["sub_images.{$key}.dimensions"] = $imageDimensionsMessage;
        }

        $messages['image.dimensions'] = $imageDimensionsMessage;

        return $messages;
    }
}
