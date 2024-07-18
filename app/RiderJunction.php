<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderJunction extends Model
{
    protected $guarded = ['id'];


    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Get the deliveryJunction that owns the RiderJunction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveryJunction(): BelongsTo
    {
        return $this->belongsTo(DeliveryJunction::class, 'junction_id');
    }

    public function getLocationAttribute()
    {
        return $this->deliveryJunction->location;
    }
}
