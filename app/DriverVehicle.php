<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'driver_id'        => 'string',
        'reg_no'        => 'string',
        'type'       => 'string',
        'plate_no'       => 'string',
        'color'       => 'string',
        'manufacturing_year'       => 'string',
        'license_category'       => 'string',
        'license_no'       => 'string',
        'license_expiry' => 'string',
        'license'    => 'image',
        'blue_book'        => 'image',
        'bluebook_expiry' => 'string',
        'picture'        => 'image',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function getLicenseAttribute($value)
    {
        return $this->imageUrl('license');
    }

    public function getBlueBookAttribute($value)
    {
        return $this->imageUrl('blue_book');
    }

    public function getPictureAttribute($value)
    {
        return $this->imageUrl('picture');
    }
}
