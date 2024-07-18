<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorNotification extends Model
{
    protected $guarded = ['id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
