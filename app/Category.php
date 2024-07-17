<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model implements ImageableContract
{
    use Imageable, Routeable;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'parent'    => 'boolean',
        'parent_id' => 'string',
        'name'      => 'string',
        'subtitle'      => 'string',
        'order'      => 'string',
        'slug'      => 'string',
        'image'     => 'image',
        'enabled' => 'boolean'
    ];

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
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
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

    public function registeredVendor()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class)->whereHas('products')->withTimestamps();
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class)->whereHas('products');
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }

    public function getEnabledAttribute($value)
    {
        return $value == 1;
    }

    public function setEnabledAttribute($value)
    {
        $this->attributes['enabled'] = $value == true ? 1 : 0;
    }
}
