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
        'blue_book_sec'        => 'image',
        'blue_book_trd'        => 'image',
        'bluebook_expiry' => 'string',
        'picture'        => 'image',
        'owner_name' => 'string',
        'owner_contact' => 'string',
        'status' => 'boolean'
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

    public function getBlueBookSecAttribute($value)
    {
        return $this->imageUrl('blue_book_sec');
    }

    public function getBlueBookTrdAttribute($value)
    {
        return $this->imageUrl('blue_book_trd');
    }

    public function getPictureAttribute($value)
    {
        return $this->imageUrl('picture');
    }
}
