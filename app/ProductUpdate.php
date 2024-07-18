<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUpdate extends Model
{
    protected $guarded = ['id'];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'product_id' => 'string',
        'product_category_id' => 'string',
        'title' => 'string',
        'slug' => 'string',
        'price' => 'string',
        'opening_stock' => 'string',
        'description' => 'string',
        'discount_type' => 'string',
        'discount' => 'string',
        'batch_no' => 'string',
        'expire_date' => 'string',
        'unit' => 'string',
        'size' => 'json',
        'color' => 'json',
        'hide' => 'boolean',
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
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
