<?php

namespace App;

use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;

class AcademyContent extends Model implements ImageableContract
{
    use Imageable;
    
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
            'title' => 'string',
            'image' => 'image',
            'url' => 'string',
            'video_url' => 'string',
            'fors' => 'string',
            'description' => 'string',
    ];
}
