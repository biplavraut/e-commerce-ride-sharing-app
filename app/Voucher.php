<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = [];

    public $columnsWithTypes = [
        'code'        => 'string',
        'amount'        => 'string',
        'user_id'       => 'string',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
