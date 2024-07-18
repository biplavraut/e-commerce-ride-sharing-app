<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorDiscount extends Model
{
    protected $guarded = [];

    public $columnsWithTypes = [
        'vendor_id' => 'string',
        'discount' => 'image',
        'status' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function getStatusAttribute($value)
    {
        return $value == 1;
    }

}
