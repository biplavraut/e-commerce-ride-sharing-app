<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Get the log associated with the Trip
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function log(): HasOne
    {
        return $this->hasOne(TripRequestLog::class, 'trip_id');
    }

    public function getPaymentModeAttribute($value)
    {
        if ($value == "gogoWallet") {
            return "gogoPoint";
        }
        return $value;
    }
}
