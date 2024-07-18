<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RentalTrip extends Model
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

    public function package()
    {
        return $this->belongsTo(RentalPackage::class, 'rental_package_id');
    }

    public function timeCalculation()
    {
        if ($this->status == "completed") {
            $diffSec = (strtotime($this->completed_at) - strtotime($this->ends_at));
            $hours = ((($diffSec / 60) / 60));
            if ($diffSec > 0) {
                return $hours>= 1 ? $hours . ' Hour(s) Exceed' : abs($hours*60).' Minute(s) Exceed';
            } elseif ($diffSec < 0) {
                return abs($hours) >= 1 ? abs($hours) . ' Hour(s) Fall Behind' : abs($hours*60).' Minute(s) Fall Behind';
            } else {
                return 'Exact Time';
            }
        }
        return '-';
    }

    public function tripId()
    {
        $id = sprintf('%03d', $this->id);
        return "#gogo20Rental{$id}";
    }
}
