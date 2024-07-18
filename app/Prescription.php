<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Prescription extends Model
{
    use Notifiable;
    //
    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'user_id' => 'string',
        'admin_id' => 'string', 'vendor_id' => 'string', 'driver_id' => 'string',
        'hospital_id' => 'string',
        'patient_name' => 'string',
        'patient_age' => 'string',
        'doctor_name' => 'string',
        'doctor_nmc' => 'string',
        'address' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'delivery_area' => 'string',
        'nearest_landmark' => 'string',
        'preferred_date' => 'string',
        'preferred_time' => 'string',
        'alternate_name' => 'string',
        'alternate_phone' => 'string',
        'additional_detail' => 'string',
        'remarks' => 'string',
        'status' => 'string',
        'shipping_fee' => 'string',
        'sub_total' => 'string',
        'vendor_total' => 'string',
        'outside_total' => 'string',
        'total' => 'string',
        'paying_total' => 'string',
        'otp' => 'string'

    ];

    public function setSubTotalAttribute($value)
    {
        $this->attributes['sub_total']   =   $value * 100;
    }

    public function getSubTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setVendorTotalAttribute($value)
    {
        $this->attributes['vendor_total']   =   $value * 100;
    }

    public function getVendorTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setOutsideTotalAttribute($value)
    {
        $this->attributes['outside_total']   =   $value * 100;
    }

    public function getOutsideTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setShippingFeeAttribute($value)
    {
        $this->attributes['shipping_fee']   =   $value * 100;
    }

    public function getShippingFeeAttribute($value)
    {
        return $value / 100;
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total']   =   $value * 100;
    }

    public function getTotalAttribute($value)
    {
        return $value / 100;
    }

    public function setPayingTotalAttribute($value)
    {
        $this->attributes['paying_total']   =   $value * 100;
    }

    public function getPayingTotalAttribute($value)
    {
        return $value / 100;
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }
    public function hasImage()
    {
        return $this->images->count() > 0;
    }
    public function getFirstImage()
    {
        if (!$this->hasImage()) {
            return myAsset('storage/images/no-image.png');
            // return '';
        }
        return $this->images->first->imageUrl();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo('App\Admin', 'admin_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver', 'driver_id');
    }

    public function hospital()
    {
        return $this->belongsTo('App\Hospital', 'hospital_id');
    }

    public function billDetail()
    {
        return $this->hasMany('App\PrescriptionBill', 'prescription_id');
    }

    public function prescriptionNo()
    {
        return "GGP" . date('Ymd', strtotime($this->created_at)) . "{$this->id}";
    }
}
