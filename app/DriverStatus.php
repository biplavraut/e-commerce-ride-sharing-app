<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverStatus extends Model
{
    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
