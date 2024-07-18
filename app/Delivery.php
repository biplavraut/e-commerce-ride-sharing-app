<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $guarded = ['id'];

    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
            'order_id' => 'string',
            'driver_id' => 'string',
            'from' => 'string',
            'to' => 'string',
            'from_lat' => 'string',
            'from_long' => 'string',
            'to_lat' => 'string',
            'to_long' => 'string',
            'vehicle_type' => 'string',
            'otp' => 'string',
            'user_otp' => 'string',
            'status' => 'string',
            'logs' => 'string',
            'cancelled_by' => 'string',
            'delivered_at' => 'string',
    ];
    

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
