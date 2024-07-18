<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InHouseRiderPaymentLog extends Model
{
    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Admin::class, 'received_by');
    }
}
