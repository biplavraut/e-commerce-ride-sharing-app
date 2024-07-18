<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripRequestLog extends Model
{
    protected $guarded = [];

    /**
     * Get the trip that owns the TripRequestLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function getRiderListAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
