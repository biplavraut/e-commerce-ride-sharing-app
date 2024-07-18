<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UtilityVoucher extends Model
{
    //
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
