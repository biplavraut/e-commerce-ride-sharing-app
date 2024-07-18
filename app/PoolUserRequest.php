<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoolUserRequest extends Model
{

    protected $guarded = ['id'];
    protected $table = "pool_request";

    public $columnsWithTypes = [
        'requested_user_id ' => 'string',
        'requested_pool_id' => 'string',
        'remarks' => 'string',
        'status' => 'string',  //0->pending,1=>accepted,2=>rejected,3=>canceled
        'seat' => 'string'
    ];

    public function getStatusAttribute($value)
    {

        return $value == 0 ? 'Pending' : ($value == 1 ? 'Accepted' : ($value == 2 ? 'Rejected' : ($value == 3 ? 'Cancelled' : 'Failed')));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'requested_user_id');
    }

    public function pool()
    {
        return $this->belongsTo(Pool::class, 'requested_pool_id');
    }
}
