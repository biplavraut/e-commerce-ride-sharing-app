<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $fillable =   ['product_id','product_option_category_id', 'service_id'];

    public function productOptionCategory()
    {
        return $this->hasOne(ProductOptionCategory::class, 'id', 'product_option_category_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
