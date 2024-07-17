<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Wishlist;

class CartProductResource extends JsonResource
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
            'category' => $this->category()->select(['id', 'name', 'image'])->first(),
            'image50' => $this->getFirstImageWatermarkCropped(150, 150),  //getFirstImageCropped(150, 150)
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->watermarkImage(), 'id' => $image->id];
            }),
            'description' => $this->description ?? '',
            'discountType' => $this->discount_type,
            'discount' => $this->discount,
            'discountPrice' => $this->discount_price,
            'tags' => $this->productTags(),
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit ?? '',
            'averageRating' => $this->averageRating() ?? 0,
            'taxAmount' => round(($this->price * $this->vat_percentage)/100),
            'serviceChargeAmount' => round(($this->price * $this->service_charge_percentage)/100),
        ];
    }
}
