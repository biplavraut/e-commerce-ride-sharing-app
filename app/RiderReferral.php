<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiderReferral extends Model
{
    protected $guarded = ['id'];

    //owner
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    //code used by 
    public function usedBy()
    {
        return $this->belongsTo(Driver::class, 'used_by');
    }
}
