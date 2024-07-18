<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model implements ImageableContract
{
    use Imageable;
    
    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
            'category_id' => 'string',
            'name' => 'string',
            'image' => 'image',
            'url' => 'string',
            'for_layout' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
