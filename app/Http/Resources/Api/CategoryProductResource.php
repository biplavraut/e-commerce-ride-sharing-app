<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->guard('api')->user();
        if (!empty($user)) {
            $prodOfferDis = $this->orderOfferDiscount();
        }
        return [
            'id' => $this->id,
            'name' => $this->title,
            'badge' => $this->badge,
            'price' => round($this->price),
            'stock' => $this->opening_stock,
            'vendor' => new VendorResource($this->vendor),
            'image50' => $this->getFirstImageCropped(150, 150),
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->cropImage(150, 150), 'id' => $image->id];
            }),
            'description' => $this->description ?? '',
            'discountType' => $prodOfferDis ? $prodOfferDis['discountType'] : $this->discount_type,
            'discount' => $prodOfferDis ? $prodOfferDis['discount'] : $this->discount,
            'discountPrice' => $prodOfferDis ? $prodOfferDis['discountPrice'] : $this->discount_price,
            'tags' => $this->productTags(),
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit ?? '',
            'productOptionCategories' =>  ProductOption::collection($this->productOptions),
        ];
    }
}
