<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class DineInForm extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'status'        => 'string',
        'total_price'        => 'string',
        'bill'        => 'image',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
