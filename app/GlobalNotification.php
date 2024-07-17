<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class GlobalNotification extends Model implements ImageableContract
{
    use Imageable;

    protected $table = 'global_notifications';

    public $columnsWithTypes = [
        'title' => 'string',
        'message' => 'string',
        'image' => 'image',
        'for' => 'string',
        'geo' => 'boolean',
        'sms' => 'boolean',
        'lat' => 'string',
        'long' => 'string',
        'radius' => 'string'
    ];
}
