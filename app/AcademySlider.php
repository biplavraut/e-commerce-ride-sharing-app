<?php

namespace App;

use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;

class AcademySlider extends Model implements ImageableContract
{
    use Imageable;
    
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
            'name' => 'string',
            'image' => 'image',
            'url' => 'string',
            'fors' => 'string',
    ];
}
