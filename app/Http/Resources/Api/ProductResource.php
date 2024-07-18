<?php

namespace App\Http\Resources\Api;

use App\Product;
use App\Wishlist;
use Illuminate\Support\Str;
use App\Http\Resources\Admin\CommonResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends CommonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArrayWithOutNullValues($request)
    {
        $isFav = false;
        $user = auth()->guard('api')->user();
        if (!empty($user)) {
            $isFav = Wishlist::where(["user_id" => $user->id, "product_id" => $this->id])->exists();
            $prodOfferDis = $this->orderOfferDiscount();
        }
        $serviceProducts = ['gogopro', 'gogofix', 'gogorent',];
        return [
            'id' => $this->id,
            'name' => $this->title,
            'badge' => count($this->badge) > 0 ? $this->badge[0] : '',
            'badges' => $this->badge,
            'price' => $this->price,
            'elitePrice' => $this->elite_price,
            'stock' => $this->opening_stock,
            'vendor' => $this->vendor ?  new VendorResource($this->vendor) : nullValue(),
            'category' => new ProductAllCategoryResource($this->category),
            'image50' =>  $this->getFirstImageCropped(150, 150), //getFirstImageWatermarkCropped(150, 150),  //getFirstImageCropped(150, 150)
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->image, 'id' => $image->id];
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
            'qas' => ProductQAResource::collection($this->qas),
            'reviews' => ProductReviewResource::collection($this->reviews),
            'pastPurchases' => $this->pastPurchase($this->userId ?? null),
            'youMayLike' => $this->similar(),
            'isFav' => $isFav,
            'taxAmount' => $prodOfferDis ? $prodOfferDis['vatAmt'] : round(($this->discount_price * $this->vat_percentage) / 100),
            'serviceChargeAmount' => $prodOfferDis ? $prodOfferDis['serviceAmt'] : round(($this->discount_price * $this->service_charge_percentage) / 100),
            'showOpeningClosingTime' => $this->showOpeningClosing(),
            'service' => $this->service ? $this->service->name : '',
            'isReturn' => $this->is_return,
            'returnDays' => $this->return_days . ' days',
            'type' => in_array($this->service['slug'], $serviceProducts) ? 'service' : 'product'
        ];
    }
}
