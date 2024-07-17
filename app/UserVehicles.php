<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;

class UserVehicles extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'user_id '        => 'string',
        'vehicle_color'        => 'string',
        'main_type'       => 'string',
        'type'       => 'string',
        'reg_no'       => 'string',
        'fuel_sharing_km'       => 'string',
        'license_image'       => 'image', 
        'vehicle_image'       => 'image', 
        'offering_seats'       => 'string',
        'check_point' => 'string',
        'features'    => 'string',
        'remarks'        => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
