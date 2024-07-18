<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UtilityCouponCode extends Model
{
    //
    protected $guarded = ['id'];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'code' => 'string',
        'amount' => 'string',
        'valid_till' => 'string',
        'users' => 'string',
    ];


    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = Str::upper($value);
    }

    /**
     * Get all of the histories for the UtilityCouponCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories(): HasMany
    {
        return $this->hasMany(UtilityCouponCodeHistory::class, 'coupon_code_id');
    }
}
