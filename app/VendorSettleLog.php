<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorSettleLog extends Model
{
    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }
}
