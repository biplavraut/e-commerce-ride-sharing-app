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
        // $productOptionCategory  =   ProductOptionCategory::get();
        // foreach ($productOptionCategory as $category) {
        //     $data[] =   array('id' => $category->id,
        //                     'title' => $category->title,
        //                     'status' => (Option::where('product_id', $this->product_id)
        //                                 ->where('product_option_category_id', $category->id)->count())?true:false);
        // }
        // return $data;
        $productOptions = Option::where('product_id', $this->product_id)->with('productOptionCategory')->get();
        $optionCategories = [];
        foreach ($productOptions as $key => $option) {
            $optionCategories[] = ['id' => $option->productOptionCategory->id, 'title' => $option->productOptionCategory->title, 'status' => true];
        }
        return $optionCategories;
    }
}
