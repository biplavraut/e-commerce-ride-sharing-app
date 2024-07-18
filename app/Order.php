<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal']   =   $value * 100;
    }

    public function getSubtotalAttribute($value)
    {
        return $value / 100;
    }

    public function setShippingFeeAttribute($value)
    {
        $this->attributes['shipping_fee']   =   $value * 100;
    }

    public function getShippingFeeAttribute($value)
    {
        return $value / 100;
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total']   =   $value * 100;
    }

    public function getTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setPayingTotalAttribute($value)
    {
        $this->attributes['paying_total']   =   $value * 100;
    }

    public function getPayingTotalAttribute($value)
    {
        return $value / 100;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function deliveryRequest()
    {
        return $this->hasOne('App\Delivery', 'order_id');
    }

    public function getPaymentModeAttribute($value)
    {
        if ($value == "gogoWallet") {
            return "gogoPoint";
        }
        return $value;
    }

    public function orderNo()
    {
        return "GGO" . date('Ymd', strtotime($this->created_at)) . "{$this->id}";
    }

    public function orderFeedbacks()
    {
        return $this->hasMany(OrderFeedback::class, 'order_id');
    }

    public function additionalDetails()
    {
        return $this->hasOne(OrderAdditionalDetail::class, 'order_ref_number', 'ref_number');
    }
}
