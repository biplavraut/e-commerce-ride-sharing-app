<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorOption extends Model
{

    protected $guarded = [];

    protected $fillable =   ['vendor_id', 'vendor_option_category_id', 'service_id'];

    public $columnsWithTypes = [
        'vendor_id'        => 'string',
        'vendor_option_category_id'        => 'string',
        'order'        => 'string',
        'service_id' => 'string',
    ];

    public function vendorOptionCategory()
    {
        return $this->hasOne(VendorOptionCategory::class, 'id', 'vendor_option_category_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }
}
