<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;

use Illuminate\Database\Eloquent\Model;

class PrescriptionBill extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = [];
    //
    public $columnsWithTypes = [
        'prescription_id' => 'string',
        'admin_id' => 'string',
        'vendor_id' => 'string',
        'driver_id' => 'string',
        'image' => 'image',
        'bill_amount' => 'string',
        'type' => 'string',
        'vendor_name' => 'string'
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
