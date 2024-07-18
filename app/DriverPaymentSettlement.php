<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverPaymentSettlement extends Model
{
    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
