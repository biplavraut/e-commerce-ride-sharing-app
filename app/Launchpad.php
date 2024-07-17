<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class Launchpad extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'launchpad_category_id' => 'string',
        'name' => 'string',
        'image' => 'image',
        'description' => 'string',
        'url' => 'string',
        'hide' => 'boolean',
        'order' => 'string'
    ];

    public function category()
    {
        return $this->belongsTo('App\LaunchpadCategory', 'launchpad_category_id');
    }

    public function watermark()
    {
        return $this->cropWithWatermarkImage(500, 500);
    }
}
