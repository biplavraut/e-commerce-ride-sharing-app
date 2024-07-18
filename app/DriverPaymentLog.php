<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverPaymentLog extends Model
{
    protected $guarded = ['id'];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function task()
    {
        if ($this->task_id) {
        }
        return '';
    }
}
