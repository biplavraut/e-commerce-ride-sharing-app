<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Admin\TripResource;

class PaymentLog extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function task()
    {
        if ($this->type == "order") {
            return $this->belongsTo(Order::class, 'task_id');
        }
        if ($this->type == "trip") {
            return $this->belongsTo(Trip::class, 'task_id');
        }
    }

    public function paymentTask()
    {
        if ($this->type == "order") {
            return $this->task()->select(['id', 'ref_number', 'status'])->first();
        } else if ($this->type == "trip") {
            return new TripResource($this->task);
        }
    }

    public function vendor()
    {
        if ($this->type == "order" && $this->task_id) {
            return $this->task->vendor()->select(['business_name', 'phone', 'email'])->first();
        }
        return 'gogo20';
    }

    public function service()
    {
        if ($this->type == "order" && $this->task_id) {
            return  $this->task->vendor->services()->first()->name;
        } else if ($this->type == "trip") {
            return 'Ride Hailing';
        }
        return 'Ride Hailing';
    }
}
