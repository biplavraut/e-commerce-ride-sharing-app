<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo('App\Driver', 'driver_id');
    }

    public function getComplementAttribute($value)
    {
        return json_decode($value);
    }
}
