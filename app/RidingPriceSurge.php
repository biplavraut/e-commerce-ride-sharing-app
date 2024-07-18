<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RidingPriceSurge extends Model
{
    protected $guarded = ['id'];

    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'title' => 'string',
        'from' => 'string',
        'to' => 'string',
        'price' => 'string'
    ];

    /**
     * Get the fare that owns the RidingPriceSurge
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fare(): BelongsTo
    {
        return $this->belongsTo(RidingFare::class, 'riding_fare_id');
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }
}
