<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $guarded = ['id'];

    //owner
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //code used by 
    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
