<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'code' => $this->code,
            'badge' => $this->badge,
            'price' => $this->price,
            'image50' => $this->getFirstImageCropped(150, 150),
            'opening_stock' => $this->opening_stock,
            'category' => $this->rootCategory($this->category->id ?? null),
            'subCategory' => $this->subCategory($this->category->id ?? null) ?? ['name' => 'N\\A', 'id' => null],
            'subChildCategory' => $this->childCategory($this->category->id ?? null) ?? ['name' => 'N\\A', 'id' => null],
            'vendor' => $this->vendor,
        ];
    }
}
