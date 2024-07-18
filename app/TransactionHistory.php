<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $guarded = ['id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transId()
    {
        return "#" . date('Ymd', strtotime($this->created_at)) . "{$this->id}";
    }

    public function getMessageAttribute()
    {
        if ($this->type == "paid") {
            return "Paid for " . $this->from . " using " . $this->payment_mode;
        } else if ($this->type == "received") {
            if ($this->from == "Load gogoPoint") {
                return "gogoPoint Loaded from " . $this->payment_mode;
            }
            return "gogoPoint received from " . $this->from;
        }
    }
}
