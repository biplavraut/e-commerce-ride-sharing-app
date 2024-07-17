<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Api\ProductOption;
use App\ProductOptionCategory;

class ProductResource extends CommonResource
{
    /**
     * Transform the resource into an array by changing null values to empty string.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArrayWithoutNullValues($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'code' => $this->code,
            'badge' => $this->badge,
            'price' => $this->price,
            'price1' => $this->price_1,
            'vendor' => $this->vendor()->select(['id', 'business_name'])->first(),
            'opening_stock' => $this->opening_stock,
            'category' => $this->rootCategory($this->category->id ?? null),
            'subCategory' => $this->subCategory($this->category->id ?? null) ?? ['name' => 'N\\A', 'id' => null],
            'subChildCategory' => $this->childCategory($this->category->id ?? null) ?? ['name' => 'N\\A', 'id' => null],
            'cat' => $this->category_id,
            'vendor' => $this->vendor,
            'image50' => $this->getFirstImageCropped(50, 50),
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->cropImage(150, 150), 'id' => $image->id];
            }),
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'discount_price' => $this->discount_price,
            'tags' => $this->productTags(),
            'size' => $this->size,
            'color' => $this->color,
            'prescription_required' => $this->prescription_required == 1,
            'batch_no' => $this->batch_no,
            'expire_date' => $this->expire_date,
            'unit' => $this->unit,
            'verified' => $this->isVerified(),
            'hide' => $this->hide == 1,
            'is_default' => $this->is_default == 1,
            'productOptionCategories' => ProductOption::collection($this->productOptions),
            'vat_percentage' => $this->vat_percentage,
            'service_charge_percentage' => $this->service_charge_percentage,
        ];
    }
}
