<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model implements ImageableContract
{
    use Imageable, Routeable;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'category_id' => 'string',
        'parent'    => 'boolean',
        'parent_id' => 'string',
        'name'      => 'string',
        'batch'      => 'string',
        'slug'      => 'string',
        'image'     => 'image',
        'order' => 'string',
    ];

    public function service()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function isParent()
    {
        return $this->parent == 1;
    }

    public function isChild()
    {
        return !is_null($this->parent_id);
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('order');
    }

    public function childrenWithProductsOnly()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('order');

        // ->whereHas("products", function ($query) {
        //     $query->where('hide', 0)->Where('verified', 1);
        // });
    }

    public function getBatchAttribute($value)
    {
        if ($value == "null") {
            return '';
        }
    }

    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeNonRoot($query)
    {
        return $query->whereNotNull('parent_id');
        collect([])->sum();
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('verified', 1)->where('hide', 0)->whereHas('vendor', function ($query) {
            $query->where('status', 1);
            $query->where('verified', 1);
        });
    }

    public function allChild()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->whereHas('products')->orderBy('order');
    }

    public function originalService()
    {
        if (!$this->parentCategory) {
            return $this->service;
        } else {
            $second = $this->parentCategory;
            if (!$second->parentCategory) {
                return $second->service;
            } else {
                $trd = $this->parentCategory->parentCategory;
                if (!$trd->parentCategory) {
                    return $trd->service;
                } else {
                    return $trd->parentCategory->service;
                }
            }
        }
    }

    public function filteredCategory($vendorId)
    {
        $finals = [];
        foreach ($this->children as $key => $child) {
            if ($child->products()->where('vendor_id', $vendorId)->count() > 0 || $child->children()->count() > 0) {
                $finals[] = $child->id;
            }
        }

        return ProductCategory::whereIn('id', $finals)->where(function ($query) {
            $query->whereHas('products');
            $query->orWhereHas('children');
        })->orderBy('order')->get();
    }

    public function isParentOfAll()
    {
        if (!$this->parentCategory) {
            return true;
        } else {
            return false;
        }
    }
}
