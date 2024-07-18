<?php

namespace App\Http\Resources\Api;

use App\ProductOption as Option;
use App\ProductOptionCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOption extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $productOptions = Option::where('product_id', $this->product_id)->whereHas('productOptionCategory')->with('productOptionCategory')->get();
        $optionCategories = [];
        foreach ($productOptions as $key => $option) {
            $optionCategories[] = ['id' => $option->productOptionCategory->id, 'title' => $option->productOptionCategory->title, 'status' => true];
        }
        return $optionCategories;
    }
}
