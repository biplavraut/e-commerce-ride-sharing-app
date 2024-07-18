<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RideOfferConf extends Model
{
    //
    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'offer_title'             => 'string',
        'no_of_rides'             => 'string',
        'discount'             => 'string',
        'from'             => 'string',
        'to' => 'string',
        'enabled' => 'boolean'
    ];
}
