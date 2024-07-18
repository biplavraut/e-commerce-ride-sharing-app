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
        $user = auth()->guard('api')->user();
        if (!empty($user)) {
            $prodOfferDis = $this->orderOfferDiscount();
        }
        return [
            'id' => $this->id,
            'name' => $this->title,
            'badge' => count($this->badge) > 0 ? $this->badge[0] : '',
            'badges' => $this->badge,
            'price' => $this->price,
            'elitePrice' => $user->elite == 1 ? $this->elite_price : 0,
            'stock' => $this->hide == 1 ? 0 : $this->opening_stock,
            'vendor' => $this->vendor ?  new VendorResource($this->vendor) : null,
            'category' => $this->category()->select(['id', 'name', 'image'])->first(),
            'image50' => $this->getFirstImageCropped(150, 150),  //getFirstImageCropped(150, 150)
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->watermarkImage(), 'id' => $image->id];
            }),
            'description' => $this->description ?? '',
            'discountType' => $prodOfferDis ? $prodOfferDis['discountType'] : $this->discount_type,
            'discount' => $prodOfferDis ? $prodOfferDis['discount'] : $this->discount,
            'discountPrice' => $prodOfferDis ? $prodOfferDis['discountPrice'] : $this->discount_price,
            'tags' => $this->productTags(),
            'size' => $this->size,
            'color' => $this->color,
            'unit' => $this->unit ?? '',
            'averageRating' => $this->averageRating() ?? 0,
            'taxAmount' => $prodOfferDis ? $prodOfferDis['vatAmt'] : round(($this->discount_price * $this->vat_percentage) / 100),
            'serviceChargeAmount' => $prodOfferDis ? $prodOfferDis['serviceAmt'] : round(($this->discount_price * $this->service_charge_percentage) / 100),
            'actualPriceIncVat' => $prodOfferDis['actualPriceIncVat'],
            'discountPriceIncVat' => $prodOfferDis['discountPriceIncVat'],
        ];
    }
}
