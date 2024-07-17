<?php

namespace App;

use App\Http\Resources\Api\PastPurchaseProductResource;
use App\Http\Resources\Api\SimilarProductResource;
use Illuminate\Database\Eloquent\Model;

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
        'badge' => 'string',
        'price' => 'string',
        'price_1' => 'string',
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
        return $this->belongsTo(ProductCategory::class, 'product_category_id')->withDefault(function () {
            return ['name' => 'N\\A', 'id' => null];
        });
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
            return myAsset('storage/images/no-image.png');
        }
        return $this->images->first->imageUrl();
    }


    public function getFirstImageCropped(int $x = 100, int $y = 100)
    {
        if (!$this->hasImage()) {
            return myAsset('storage/images/no-image.png');
        }

        return $this->images->first()->cropImage($x, $y);
    }

    public function getFirstImageWatermarkCropped(int $x = 100, int $y = 100)
    {
        if (!$this->hasImage()) {
            return myAsset('storage/images/no-image.png');
        }

        return $this->images->first()->cropWithWatermarkImage($x, $y);
    }

    public function getDiscountPriceAttribute()
    {
        $discount = 0;
        $type = $this->discount_type;
        $val = $this->discount;
        if ($type == 'amount') {
            $discount = ($this->price) - $val;
        }

        if ($type == 'percent') {
            $discount = round($this->price - ($this->price) * ($val / 100));
        }

        return $discount;
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

    public function rootCategory($id)
    {
        if ($id != null) {
            $childCategory = ProductCategory::find($id);

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

    public function subCategory($id)
    {
        if ($id != null) {
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

    public function childCategory($id)
    {
        if ($id != null) {
            $childCategory = ProductCategory::find($id);

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
            $orderItems = OrderItem::where('user_id', $userId)->Where('vendor_id', $this->vendor->id)->select(['product_id', 'vendor_id'])->get();
            $orderItems = $orderItems->filter(function ($item) {
                return $item['product_id'] != $this->id;
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
}
