<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Wishlist;

class ServiceProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isFav = false;
        $user = auth()->guard('api')->user();
        if(!empty($user)){
            $isFav = Wishlist::where(["user_id"=>$user->id,"product_id"=>$this->product->id])->exists();
        }
        return [
            'id' => $this->product->id,
            'name' => $this->product->title,
            'badge' => $this->product->badge ?? "",
            'price' => $this->product->price,
            'stock' => $this->product->opening_stock,
            'vendor' => $this->product->vendor ?  new VendorResource($this->product->vendor) : null,
            'category' => $this->product->category()->select(['id', 'name', 'image'])->first(),
            'image50' => $this->product->getFirstImageWatermarkCropped(150, 150),  //getFirstImageCropped(150, 150)
            'images' => $this->product->images->map(function ($image) {
                return ['image' => $image->watermarkImage(), 'id' => $image->id];
            }),
            // 'description' => $this->product->description ?? '',
            'discountType' => $this->product->discount_type,
            'discount' => $this->product->discount,
            'discountPrice' => $this->product->discount_price,
            'tags' => $this->product->productTags(),
            'size' => $this->product->size,
            'color' => $this->product->color,
            'unit' => $this->product->unit ?? '',
            'averageRating' => $this->product->averageRating() ?? 0,
            'qas' => ProductQAResource::collection($this->product->qas),
            'reviews' => ProductReviewResource::collection($this->product->reviews),
            'pastPurchases' => $this->product->pastPurchase($this->product->userId ?? null),
            'youMayLike' => $this->product->similar(),
            'isFav' => $isFav,
            'taxAmount' => round(($this->product->price * $this->product->vat_percentage)/100),
            'serviceChargeAmount' => round(($this->product->price * $this->product->service_charge_percentage)/100),
        ];
    }
}
