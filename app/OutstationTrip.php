<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutstationTrip extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function tripId()
    {
        $id = sprintf('%03d', $this->id);
        return "#gogo20Outstation{$id}";
    }
}
