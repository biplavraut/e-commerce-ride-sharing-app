<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $guarded = ['id'];
    
    public $columnsWithTypes = [
        'product_id'        => 'string',
        'user_id'        => 'string'
    ];

}
