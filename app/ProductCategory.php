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
    ];

    public function service()
    {
        return $this->belongsTo(Category::class, 'id');
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
        return $this->hasMany(ProductCategory::class, 'parent_id')->whereHas("products");
    }

    // public function childrenWithProductsOnly(){
    //     return $this->hasMany(ProductCategory::class, 'parent_id')->whereHas("products");
    // }
    public function childrenWithProductsOnly()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->whereHas("products", function ($query) {
            $query->where('hide', 0)->Where('verified', 1);
        });
    }

    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id')->whereHas("products");
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
        return $this->hasMany(Product::class);
        // ->whereHas("vendor", function ($query) {
        //     $query->where("status", 1);
        // });
    }

    public function allChild()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }
}
