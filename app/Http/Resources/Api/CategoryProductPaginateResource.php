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
        if (!empty($user)) {
            $isFav = Wishlist::where(["user_id" => $user->id, "product_id" => $this['id']])->exists();
        }
        $product = Product::find($this['id']);
        return [
            'id' => $this['id'],
            'name' => $this['title'],
            'badge' => $this['badge'] ?? "",
            'price' => $this['price'],
            'stock' => $this['opening_stock'],
            // 'vendor' => $this['vendor_id'] ?  new VendorResource(Vendor::find($this['vendor_id'])) : null,
            'category' => $product->category()->select('id', 'name', 'image')->first(),
            'image50' => $product->getFirstImageWatermarkCropped(150, 150),
            'images' => $product->images->map(function ($image) {
                return ['image' => $image->watermarkImage(), 'id' => $image->id];
            }),
            'description' => $this['description'] ?? '',
            'discountType' => $this['discount_type'],
            'discount' => $this['discount'],
            'discountPrice' => $product->discount_price,
            'tags' => $product->productTags(),
            'size' => $this['size'],
            'color' => $this['color'],
            'unit' => $this['unit'] ?? '',
            'averageRating' => $product->averageRating() ?? 0,
            'qas' => ProductQAResource::collection($product->qas),
            'reviews' => ProductReviewResource::collection($product->reviews),
            'pastPurchases' => $product->pastPurchase($this->userId ?? null),
            'youMayLike' => $product->similar(),
            'isFav' => $isFav,
            'taxAmount' => round(($this['price'] * $this['vat_percentage']) / 100),
            'serviceChargeAmount' => round(($this['price'] * $this['service_charge_percentage']) / 100),
        ];
    }
}
