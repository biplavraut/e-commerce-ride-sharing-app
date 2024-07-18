<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Admin\CommonResource;

use Illuminate\Http\Resources\Json\JsonResource;

class DealProductResource extends JsonResource
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
            'id' => $this->product_id,
            'name' => $this->product->title,
            'price' => round($this->product->price),
            'image50' =>  $this->product->getFirstImageCropped(150, 150),  //getFirstImageCropped(150, 150)
            'images' => $this->product->images->map(function ($image) {
                return ['image' => $image->watermarkImage(), 'id' => $image->id];
            }),
            'discount' => $this->product->discount,
            'dealDiscountPercent' => (float) $this->discount,
            'elitePrice' => $this->product->elite_price,
            'stock' => $this->product->opening_stock,
            'size' => $this->product->size,
            'color' => $this->product->color,
            'unit' => $this->product->unit ?? '',
            'taxAmount' => round(($this->product->discount_price * $this->product->vat_percentage) / 100),
            'serviceChargeAmount' => round(($this->product->discount_price * $this->product->service_charge_percentage) / 100),
        ];
    }
}
