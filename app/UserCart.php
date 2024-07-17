<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    protected $guarded = ['id'];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
