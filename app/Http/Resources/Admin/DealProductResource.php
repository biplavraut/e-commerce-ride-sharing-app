<?php

namespace App\Http\Resources\Admin;

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
            'id'  =>  $this->id,
            'dealId' =>  $this->deal_id,
            'productId' =>  $this->product_id,
            'discount' => $this->discount,
            'name' => $this->product->title,
            'price' => $this->product->price,
            'image50' => $this->product->getFirstImageCropped(150, 150),
            'images' => $this->product->images->map(function ($image) {
                return ['image' => $image->watermarkImage(), 'id' => $image->id];
            }),
            'elite_percent' => $this->product->elite_percent,
            'created_at' => $this->created_at
        ];
    }
}
