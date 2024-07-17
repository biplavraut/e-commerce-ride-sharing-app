<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Wishlist;

class PastPurchaseProductResource extends JsonResource
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
            'vendorId' => $this->product->vendor_id,
            'badge' => $this->product->badge ?? "",
            'price' => $this->product->price,
            'stock' => $this->product->opening_stock,
            'image50' => $this->product->getFirstImageCropped(150, 150),
            'images' => $this->product->images->map(function ($image) {
                return ['image' => $image->cropImage(150, 150), 'id' => $image->id];
            }),
            'description' => $this->product->description,
            'discountType' => $this->product->discount_type,
            'discount' => $this->product->discount,
            'discountPrice' => $this->product->discount_price,
            'size' => $this->product->size,
            'color' => $this->product->color,
            'unit' => $this->product->unit,
            'isFav' => $isFav,
        ];
    }
}
