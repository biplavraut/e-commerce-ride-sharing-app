<?php

namespace App;

use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'enabled' => 'boolean',
        'ondemand' => 'boolean',
        'opening_closing_time' => 'boolean',
        'show_product_category' => 'boolean'
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
        return $this->belongsToMany(Vendor::class)->whereHas('products', function ($query) {
            $query->where('hide', 0)->Where('verified', 1);
        })->withTimestamps();
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class)->orderBy('order');
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }

    public function getEnabledAttribute($value)
    {
        return $value == 1;
    }

    public function getOpeningClosingTimeAttribute($value)
    {
        return $value == 1;
    }

    public function getShowProductCategoryAttribute($value)
    {
        return $value == 1;
    }

    public function getOndemandAttribute($value)
    {
        return $value == 1;
    }

    public function setEnabledAttribute($value)
    {
        $this->attributes['enabled'] = $value == true ? 1 : 0;
    }

    /**
     * Get all of the productOptionCategories for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productOptionCategories(): HasMany
    {
        return $this->hasMany(ProductOptionCategory::class, 'service_id');
    }

    /**
     * Get all of the vendorOptionCategories for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendorOptionCategories(): HasMany
    {
        return $this->hasMany(VendorOptionCategory::class, 'service_id');
    }

    /**
     * Get all of the vendorOptionCategories for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function layoutManager(): HasMany
    {
        return $this->hasMany(LayoutManager::class, 'service_id');
    }
}
