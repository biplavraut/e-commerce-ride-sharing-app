<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schedule()
    {
        return $this->hasOne(ScheduleTrip::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getDistance1Attribute()
    {
        return  number_format((float) str_replace("km", "", $this->distance), 2, '.', '');
    }

    public function tripId()
    {
        $id = sprintf('%03d', $this->id);
        return "#gogo20{$id}";
    }
}
