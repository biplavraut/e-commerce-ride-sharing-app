<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductUpdateResource extends CommonResource
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
            'product' => new ProductResource($this->product),
            'title' => $this->title,
            'price' => $this->price,
            'opening_stock' => $this->opening_stock,
            'category' => $this->rootCategory(),
            'subCategory' => $this->subCategory() ?? ['name' => 'N\\A', 'id' => null],
            'subChildCategory' => $this->childCategory() ?? ['name' => 'N\\A', 'id' => null],
            'cat' => $this->category_id,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'discount_price' => $this->discount_price,
            'size' => $this->size,
            'color' => $this->color,
            'batch_no' => $this->batch_no,
            'expire_date' => $this->expire_date,
            'unit' => $this->unit,
            'hide' => $this->hide == 1,
            'vat_percentage' => $this->vat_percentage,
            'service_charge_percentage' => $this->service_charge_percentage,
        ];
    }
}
