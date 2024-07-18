<?php

namespace App\Http\Resources\Api;

use App\Product;
use App\ProductCategory;
use App\Vendor;
use App\Wishlist;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductPaginateResource extends JsonResource
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
        $product = Product::find($this['id']);
        if (!empty($user)) {
            $isFav = Wishlist::where(["user_id" => $user->id, "product_id" => $this['id']])->exists();
            $prodOfferDis = $product->orderOfferDiscount();
        }
        $serviceProducts = ['gogopro', 'gogofix', 'gogorent',];
        return [
            'id' => $product->id,
            'name' => $product->title,
            'badge' => count($product->badge) > 0 ? $product->badge[0] : '',
            'badges' => $product->badge,
            'price' => round($product->price),
            'elitePrice' => round($product->elite_price),
            'stock' => $product->opening_stock,
            'vendor' => $product->vendor ?  new VendorResource($product->vendor) : nullValue(),
            'category' => new ProductAllCategoryResource($product->category),
            'image50' =>  $product->getFirstImageCropped(150, 150), //getFirstImageWatermarkCropped(150, 150),  //getFirstImageCropped(150, 150)
            'images' => $product->images->map(function ($image) {
                return ['image' => $image->image, 'id' => $image->id];
            }),
            'description' => $product->description ?? '',
            'discountType' => $prodOfferDis['discountType'],
            'discount' => $prodOfferDis['discount'],
            'discountPrice' => $prodOfferDis['discountPrice'],
            'tags' => $product->productTags(),
            'size' => $product->size,
            'color' => $product->color,
            'unit' => $product->unit ?? '',
            'averageRating' => $product->averageRating() ?? 0,
            'qas' => ProductQAResource::collection($product->qas),
            'reviews' => ProductReviewResource::collection($product->reviews),
            'pastPurchases' => $product->pastPurchase($this->userId ?? null),
            'youMayLike' => $product->similar(),
            'isFav' => $isFav,
            'taxAmount' => $prodOfferDis['vatAmt'],
            'serviceChargeAmount' => $prodOfferDis['serviceAmt'],
            'type' => in_array($product->service['slug'], $serviceProducts) ? 'service' : 'product'
        ];
    }
}
