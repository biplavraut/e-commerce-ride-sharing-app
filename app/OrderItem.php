<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];

    public function setPriceAttribute($value)
    {
        $this->attributes['price']  =   $value * 100;
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setGogoPriceAttribute($value)
    {
        $this->attributes['gogo_price']  =   $value * 100;
    }

    public function getGogoPriceAttribute($value)
    {
        return $value / 100;
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function orderReturn()
    {
        return $this->hasOne('App\OrderReturn', 'order_item_id');
    }
}
