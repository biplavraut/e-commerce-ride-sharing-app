<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class RoadBlockMessage extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = ['id'];

    protected $table = 'road_block_messages';

    public $columnsWithTypes = [
        'title' => 'string',
        'description' => 'string',
        'image' => 'image',
        'show_image_on_top' => 'boolean',
        'status' => 'boolean',
        'type' => 'string'
    ];
}
