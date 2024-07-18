<?php

namespace App\Http\Resources\Api;

use App\Product;
use App\ProductOption;
use App\Http\Resources\Api\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionCategoryResource extends JsonResource
{
    protected $category;

    public function category($value)
    {
        $this->category =   $value;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $productIds     =    ProductOption::where('service_id', $request->category_id)->Where('product_option_category_id', $this->id)->orderBy('order')->pluck('product_id')->toArray();
        $orderedIds = implode(',', $productIds);

        $products = Product::whereIn('id', $productIds)->where('verified', 1)->Where('hide', 0)->orderByRaw("FIELD(id, $orderedIds)")->limit(10)->get();
        return [
            'id'    =>  $this->id,
            'title' =>  $this->title,
            'slug'  =>  $this->slug,
            'layout'  =>  $this->layout,
            'products'  => ProductResource::collection($products)
        ];
    }
}
