<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RidingFare extends Model
{
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'vehicle' => 'string',
        'flat_price' => 'string',
        'price' => 'string',
        'night_surge' => 'string',
        'description' => 'string'
    ];

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setFlatPriceAttribute($value)
    {
        $this->attributes['flat_price'] = $value * 100;
    }

    public function getFlatPriceAttribute($value)
    {
        return $value / 100;
    }

    /**
     * Get all of the surges for the RidingFare
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function surges(): HasMany
    {
        return $this->hasMany(RidingPriceSurge::class, 'riding_fare_id');
    }
}
