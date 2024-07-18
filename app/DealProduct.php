<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DealProduct extends Model
{
    protected $guarded = [];

    public $columnsWithTypes = [
        'deal_id' => 'string',
        'product_id' => 'string',
        'discount' => 'image',
        'status' => 'string',
        'order' => 'string',
    ];

    public function deal()
    {
        return $this->belongsTo('App\Deal', 'deal_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
