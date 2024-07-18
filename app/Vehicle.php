<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $guarded = [];
    
    public $columnsWithTypes = [
        'name '        => 'string',
        'type'        => 'string',
        'image'       => 'image',
    ];
}
