<?php

namespace App;

use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;

class WebsiteSlider extends Model implements ImageableContract
{
    use Imageable;
    
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
            'slider_text' => 'string',
            'image' => 'image',
            'order' => 'string',
            'hide' => 'boolean',
    ];
}
