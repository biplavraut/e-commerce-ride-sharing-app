<?php

namespace App;

use Carbon\Carbon;
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

    public function getNightSurgeAttribute($value)
    {
        $defaultConf = DefaultConf::select(['night_surge_start', 'night_surge_end'])->first();

        $start = Carbon::parse($defaultConf->night_surge_start)->format("h:i:s");
        $end = Carbon::parse($defaultConf->night_surge_end)->format("H:i:s");

        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime < $end && $currentTime > $start) {
            return $value;
        } else {
            return 1;
        }
    }

    public function finalFare($addOnSurge)
    {
        $rawPrice = $this->price + $this->flat_price;

        if ($this->night_surge != 0 && $this->night_surge) {
            $defaultConf = DefaultConf::select(['night_surge_start', 'night_surge_end'])->first();
            $start = Carbon::parse($defaultConf->night_surge_start)->format("H:i:s");
            $end = Carbon::parse($defaultConf->night_surge_end)->format("H:i:s");

            $currentTime = Carbon::now()->format('H:i:s');

            if ($currentTime < $end && $currentTime > $start) {
                $rawPrice = $rawPrice * $this->night_surge;
            }
        }

        if ($addOnSurge != 0) {
            $rawPrice = $rawPrice * $addOnSurge;
        }

        return $rawPrice;
    }

    public function finalLessFare($addOnSurge)
    {
        $rawPrice = $this->price + $this->flat_price;
        $nightPrice = 0;
        $addOnPrice = 0;
        $totalPrice = 0;

        if ($this->night_surge != 0 && $this->night_surge) {
            $defaultConf = DefaultConf::select(['night_surge_start', 'night_surge_end'])->first();
            $start = Carbon::parse($defaultConf->night_surge_start)->format("H:i:s");
            $end = Carbon::parse($defaultConf->night_surge_end)->format("H:i:s");

            $currentTime = Carbon::now()->format('H:i:s');

            if ($currentTime < $end && $currentTime > $start) {
                $nightPrice = $rawPrice * $this->night_surge;
            }
        }

        if ($addOnSurge != 0) {
            $addOnPrice = $rawPrice * $addOnSurge;
        }

        if ($nightPrice > 0 && $addOnPrice > 0) {
            $totalPrice = ($nightPrice + $addOnPrice) - $rawPrice;
        } else if ($nightPrice > 0 && $addOnPrice < 1) {
            $totalPrice = $nightPrice;
        } else if ($nightPrice < 1 && $addOnPrice > 0) {
            $totalPrice = $addOnPrice;
        } else {
            $totalPrice = $rawPrice;
        }
        return $totalPrice;
    }
}
