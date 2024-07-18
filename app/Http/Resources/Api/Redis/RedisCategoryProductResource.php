<?php

namespace App\Http\Resources\Api\Redis;

use Illuminate\Http\Resources\Json\JsonResource;

class RedisCategoryProductResource extends JsonResource
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
            'name' => $this->name,
            'badge' => $this->badge,
            'badges' => $this->badges,
            'price' => $this->price,
            'elitePrice' => $this->elitePrice,
            'stock' => $this->stock,
            'vendor' => $this->vendor,
            'category' => $this->category,
            'image50' => $this->image50,
            'images' => $this->images,
            'description' => $this->description,
            'discountType' => $this->discountType,
            'discount' => $this->discount,
            'discountPrice' => $this->discountPrice,
            'tags' => $this->tags,
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit,
            'averageRating' => $this->averageRating,
            'qas' => $this->qas,
            'reviews' => $this->reviews,
            'pastPurchases' => $this->pastPurchases,
            'youMayLike' => $this->youMayLike,
            'isFav' => $this->isFav,
            'taxAmount' => $this->taxAmount,
            'serviceChargeAmount' => $this->serviceChargeAmount,
            'type' => $this->type ? $this->type : 'product'
        ];
    }
}
