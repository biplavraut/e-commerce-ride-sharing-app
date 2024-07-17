<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistProductResource extends JsonResource
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
            'badge' => $this->badge ?? "",
            'price' => $this->price,
            'stock' => $this->opening_stock,
            'vendor' => $this->vendor ?  new VendorResource($this->vendor) : null,
            'description' => $this->description ?? '',
            'discountType' => $this->discount_type,
            'discount' => $this->discount,
            'discountPrice' => $this->discount_price,
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit ?? '',
        ];
    }
}
