<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryJunction extends Model
{
    protected $guarded = ['id'];

    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'location' => 'string',
        'lat' => 'string',
        'long' => 'string',
    ];

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = ucfirst($value);
    }
}
