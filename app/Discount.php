<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discount';
    protected $fillable = ['discount_type','discount_value','applied_from','applied_till','status'];
}
