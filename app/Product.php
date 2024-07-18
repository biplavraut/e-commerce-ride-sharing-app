<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Api\SimilarProductResource;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Resources\Api\PastPurchaseProductResource;
use Exception;

class Product extends Model
{
    protected $guarded = ['id'];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'product_category_id' => 'string',
        'vendor_id' => 'string',
        'title' => 'string',
        'code' => 'string',
        'slug' => 'string',
        'badge' => 'json',
        'price' => 'string',
        'price_1' => 'string',
        'elite_percent' => 'string',
        'opening_stock' => 'string',
        'description' => 'string',
        'discount_type' => 'string',
        'discount' => 'string',
        'batch_no' => 'string',
        'expire_date' => 'string',
        'unit' => 'string',
        'size' => 'json',
        'color' => 'json',
        'prescription_required' => 'boolean',
        'hide' => 'boolean',
        'is_default' => 'boolean',
        'verified' => 'boolean',
        'is_return' => 'boolean',
        'return_days' => 'string',
        'vat_percentage' => 'string',
        'service_charge_percentage' => 'string',
    ];

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function getPrice1Attribute($value)
    {
        return $value / 100;
    }

    public function setPrice1Attribute($value)
    {
        $this->attributes['price_1'] = $value * 100;
    }

    public function getSizeAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function getColorAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function getBadgeAttribute($value)
    {
        if (json_decode($value, true)) {
            return json_decode($value, true);
        }
        return !Str::contains($value, '[]') && strlen($value) > 0 ? [$value] : [];
    }

    public function setHideAttribute($value)
    {
        $this->attributes['hide'] = $value == true ? 1 : 0;
    }

    public function setVerifiedAttribute($value)
    {
        $this->attributes['verified'] = $value == true ? 1 : 0;
    }

    public function isVerified()
    {
        return $this->verified == 1;
    }

    public function verify()
    {
        $this->verified = true;
        $this->save();
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function hasImage()
    {
        return $this->images->count() > 0;
    }
    public function getFirstImage()
    {
        if (!$this->hasImage()) {
            // return myAsset('storage/images/no-image.png');
            return '';
        }
        return $this->images->first->imageUrl();
    }


    public function getFirstImageCropped(int $x = 100, int $y = 100)
    {
        if (!$this->hasImage()) {
            return Str::contains(request()->route()->uri, 'admin') || Str::contains(request()->route()->uri, 'vendor/') ? myAsset('storage/images/no-image.png') : '';
        }
        return $this->images->first()->image; //cropImage($x, $y);
    }

    public function getFirstImageWatermarkCropped(int $x = 100, int $y = 100)
    {
        if (!$this->hasImage()) {
            // return myAsset('storage/images/no-image.png');
            return '';
        }

        return $this->images->first()->watermarkInOrginalImage();
        // return $this->images->first()->cropWithWatermarkImage($x, $y);
    }

    public function getVendorDiscountAttribute()
    {
        $disc = VendorDiscount::select('discount')->where('vendor_id', $this->vendor_id)->where('status', 1)->first();
        return $disc->discount ?? 0;
    }

    public function getDiscountAttribute($value)
    {
        $dealDiscount = 0;
        if ($this->dealProducts()->count() > 0) {
            foreach ($this->dealProducts as $key => $first) {
                if ($first->deal->status == 1 && $first->deal->from <= date('Y-m-d H:i:s') && $first->deal->to > date('Y-m-d H:i:s')) {
                    $dealDiscount = $first->discount;
                    break;
                }
            }
        }
        return $dealDiscount > 0 ? (int)$dealDiscount : ($this->vendor_discount > 0 ? (int)$this->vendor_discount : (int)$value);
    }

    public function getDiscountTypeAttribute($value)
    {
        $dealDiscount = 0;
        if ($this->dealProducts()->count() > 0) {
            foreach ($this->dealProducts as $key => $second) {
                if ($second->deal->status == 1 && $second->deal->from <= date('Y-m-d H:i:s') && $second->deal->to > date('Y-m-d H:i:s')) {
                    $dealDiscount = $second->discount;
                    break;
                }
            }
        }
        return $dealDiscount > 0 ? "percent" : ($this->vendor_discount > 0 ? "percent" : $value);
    }

    public function getDiscountPriceAttribute()
    {
        $discount = 0;

        $dealDiscount = 0;
        $vendorDiscount = 0;
        if ($this->dealProducts()->count() > 0) {
            foreach ($this->dealProducts as $key => $third) {
                if ($third->deal->status == 1 && $third->deal->from <= date('Y-m-d H:i:s') && $third->deal->to > date('Y-m-d H:i:s')) {
                    $dealDiscount = $third->discount;
                    break;
                }
            }
        }
        if ($dealDiscount > 0) {
            $discount = $this->price - round(($this->price * $dealDiscount) / 100);
            return (int)$discount;
        } elseif ($this->vendor_discount > 0) {
            $vendorDiscount = $this->vendor_discount;
            $discount = $this->price - round(($this->price * $vendorDiscount) / 100);
            return (int)$discount;
        } else {
            $type = $this->discount_type;
            $val = $this->discount;
            if ($type == 'amount') {
                $discount = ($this->price) - $val;
            }

            if ($type == 'percent') {
                $discount = round($this->price - ($this->price) * ($val / 100));
            }
            return (int)$discount;
        }
    }



    public function getElitePriceAttribute()
    {
        return round(($this->price * $this->elite_percent) / 100);
    }

    public function getDiscountPercentAttribute()
    {
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function productTags()
    {
        return $this->tags()->where('product_id', $this->id)->pluck('name');
    }

    public function rootCategory()
    {
        if ($this->product_category_id != null) {
            $childCategory = ProductCategory::find($this->product_category_id);

            if ($childCategory->parent_id) {
                $subCategory = ProductCategory::find($childCategory->parent_id);

                if ($subCategory->parent_id) {
                    $category = ProductCategory::find($subCategory->parent_id);
                } else {
                    return $subCategory;
                }
            } else {
                return $childCategory;
            }
        } else {
            return ['name' => 'N\\A', 'id' => null];
        }

        if ($category) {
            return $category;
        } elseif ($subCategory) {
            return $subCategory;
        } else {
            return $childCategory;
        }
    }

    public function subCategory()
    {
        if ($this->product_category_id != null) {
            $childCategory = $this->category;

            if ($childCategory->parentCategory) {
                $subCategory = $childCategory->parentCategory;
                if ($subCategory->parent_id) {
                    return $subCategory;
                } else {
                    return ProductCategory::where('id', $childCategory->id)->first();
                }
            } else {
            }
        } else {
            return ['name' => 'N\\A', 'id' => null];
        }
    }

    public function childCategory()
    {
        if ($this->product_category_id != null) {
            $childCategory = ProductCategory::find($this->product_category_id);

            if ($childCategory->parent_id) {
                $subCategory = ProductCategory::find($childCategory->parent_id);

                if (!$subCategory) {
                    return ['name' => 'N\\A', 'id' => null];
                }

                if ($subCategory->parent_id) {
                    $category = ProductCategory::find($subCategory->parent_id);

                    if ($category) {
                        return $childCategory;
                    } else {
                        return ['name' => 'N\\A', 'id' => null];
                    }
                }
            } else {
                return ['name' => 'N\\A', 'id' => null];
            }
        } else {
            return ['name' => 'N\\A', 'id' => null];
        }
    }

    public function getServiceAttribute()
    {
        try {
            if ($this->rootCategory()->id == null) {
                return nullValue();
            }
        } catch (\Throwable $th) {
            try {
                if ($this->subCategory()->id == null) {
                    return nullValue();
                }
            } catch (Exception $e) {
                return nullValue();
            }
        }
        return $this->rootCategory()->service;
    }

    public function qas()
    {
        return $this->hasMany(ProductQa::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReviewRating::class)->where('verified', 1);
    }

    public function averageRating()
    {
        return $this->reviews()->count() > 0 ? round($this->reviews()->sum('rating') / $this->reviews()->count(), 2) : 0;
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function tag()
    {
        return $this->tags()->where('product_id', $this->id)->pluck('name')[0] ?? '';
    }

    public function pastPurchase($userId)
    {
        if ($userId) {
            $orderItems = OrderItem::where('user_id', $userId)->Where('vendor_id', $this->vendor->id)->get();
            $orderItems = $orderItems->filter(function ($item) {
                return $item['product_id'] != $this->id && $item->order->status == "DELIVERED";
            });
            return PastPurchaseProductResource::collection($orderItems->unique());
        } else {
            return [];
        }
    }

    public function similar()
    {
        $similars = null;
        if ($this->tag()) {
            $tag = Tag::where('name', $this->tag())->with('products')->first();

            $similars = $tag->products->filter(function ($row) {
                if ($row->id != $this->id) {
                    return $row;
                }
            });

            if ($similars) {
                return SimilarProductResource::collection($similars);
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function showOpeningClosing()
    {
        if ($this->rootCategory()) {
            return $this->rootCategory()->service->opening_closing_time == 1;
        }

        return false;
    }

    public function orderOfferDiscount()
    {
        $user = auth()->guard('api')->user();
        // Check Active order offers
        $vendorOrderOfferApplicable = $this->vendor->order_offer_applicable == 1;
        $offerActive = false;
        $offerDiscount = 0;
        $activeOrderOffer = OrderOfferConf::where('enabled', 1)
            ->where('from', '<=', date('Y-m-d H:i:s'))
            ->where('to', '>', date('Y-m-d H:i:s'))->first();
        if ($activeOrderOffer && $vendorOrderOfferApplicable) {
            // Check if user is eligible for offer
            $countOrders = Order::where('user_id', $user->id)
                ->distinct()
                ->where('status', '!=', 'CANCELLED')
                ->where('created_at', '>=', $activeOrderOffer->from)
                ->where('created_at', '<=', $activeOrderOffer->to)->count('ref_number');
            $remainingOrders = $activeOrderOffer->no_of_orders - $countOrders;
            if ($remainingOrders > 0) {
                $offerActive = true;
                $offerDiscount = $activeOrderOffer->discount;
            } else {
                $offerActive = false;
                $offerDiscount = $activeOrderOffer->discount;
            }
        }

        $orgServiceCharge = round(($this->price * $this->service_charge_percentage) / 100);
        $actualVatCharge = round((($this->price + $orgServiceCharge) * $this->vat_percentage) / 100);
        $actiualPriceIncVat = round($this->price + $orgServiceCharge + $actualVatCharge);

        $discountType       = $this->discount_type;
        $discount   = $this->discount;
        $discountPrice = $this->discount_price;
        $eliteDiscount = 0;
        if ($user->elite == 1) {
            $eliteDiscount = $this->elite_price;
        }
        $finalSellingPrice = $discountPrice - $eliteDiscount;
        $serviceCharge = round(($finalSellingPrice * $this->service_charge_percentage) / 100);
        $vat = round((($finalSellingPrice + $serviceCharge) * $this->vat_percentage) / 100);
        $discountPriceIncVat = $finalSellingPrice + $serviceCharge + $vat;
        if ($offerActive) {
            $offerDiscountPrice = round($this->price - ($this->price * $offerDiscount) / 100);
            if ($offerDiscountPrice < $this->discount_price) {
                $discountType       = 'percent';
                $discount   = $offerDiscount;
                $discountPrice = $offerDiscountPrice;
                $finalSellingPrice = $discountPrice - $eliteDiscount;
                $serviceCharge = round(($finalSellingPrice * $this->service_charge_percentage) / 100);
                $vat = round((($finalSellingPrice + $serviceCharge) * $this->vat_percentage) / 100);
                $discountPriceIncVat = $finalSellingPrice + $serviceCharge + $vat;
            }
        }
        return ['actualPriceIncVat' => $actiualPriceIncVat, 'discountPriceIncVat' => $discountPriceIncVat, 'discountType' => $discountType, 'discount' => $discount, 'discountPrice' => $discountPrice, 'vatAmt' => $vat, 'serviceAmt' => $serviceCharge];
    }

    /**
     * Get the update associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productUpdate(): HasOne
    {
        return $this->hasOne(ProductUpdate::class, 'product_id');
    }

    /**
     * Get all of the dealProducts for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dealProducts(): HasMany
    {
        return $this->hasMany(DealProduct::class);
    }
}
