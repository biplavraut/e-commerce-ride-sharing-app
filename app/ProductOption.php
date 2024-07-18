<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $guarded = [];

    protected $fillable =   ['product_id', 'product_option_category_id', 'service_id'];

    public $columnsWithTypes = [
        'product_id'        => 'string',
        'product_option_category_id'        => 'string',
        'order'        => 'string',
        'service_id' => 'string',
    ];

    public function productOptionCategory()
    {
        return $this->hasOne(ProductOptionCategory::class, 'id', 'product_option_category_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
