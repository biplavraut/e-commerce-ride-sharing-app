<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;

class PremiumPlace extends Model implements ImageableContract
{
    use Routeable, Imageable;

    protected $guarded = ['id'];

    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'image' => 'image',
        'location' => 'string',
        'lat' => 'string',
        'long' => 'string',
        'price' => 'string',
        'radius' => 'string',
        'popular' => 'boolean',
        'hide' => 'boolean'
    ];

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = ucfirst($value);
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function setPopularAttribute($value)
    {
        $this->attributes['popular'] = $value == true ? 1 : 0;
    }

    public function setHideAttribute($value)
    {
        $this->attributes['hide'] = $value == true ? 1 : 0;
    }
}
