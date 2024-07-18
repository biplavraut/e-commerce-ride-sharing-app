<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class SimilarProductResource extends JsonResource
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
            'name' => $this->title,
            'badge' => count($this->badge) > 0 ? $this->badge[0] : '',
            'badges' => $this->badge,
            'price' => $this->price,
            'stock' => $this->opening_stock,
            'vendorId' => $this->vendor_id,
            'image50' => $this->getFirstImageCropped(150, 150),
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->cropImage(150, 150), 'id' => $image->id];
            }),
            'description' => $this->description,
            'discountType' => $this->discount_type,
            'discount' => $this->discount,
            'discountPrice' => $this->discount_price,
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit,
        ];
    }
}
