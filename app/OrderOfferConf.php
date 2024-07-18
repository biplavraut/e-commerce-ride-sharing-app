<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderOfferConf extends Model
{
    //
    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'order_title'             => 'string',
        'no_of_orders'             => 'string',
        'discount'             => 'string',
        'from'             => 'string',
        'to' => 'string',
        'enabled' => 'boolean'
    ];
}
