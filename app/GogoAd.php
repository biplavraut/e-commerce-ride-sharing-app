<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class GogoAd extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $columnsWithTypes = [
        'title' => 'string',
        'image' => 'image',
        'url' => 'string',
        'hide' => 'boolean',
    ];
}
