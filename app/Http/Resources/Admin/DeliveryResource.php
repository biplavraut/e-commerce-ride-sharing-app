<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Api\Ride\DriverResource;
use App\Http\Resources\Api\Ride\OrderResource;

class DeliveryResource extends CommonResource
{
    /**
     * Transform the resource into an array by changing null values to empty string.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArrayWithoutNullValues($request)
    {
        return [
            'id' => $this->id,
            'orderNo'       =>  date('Ymd', strtotime($this->order->created_at)) . $this->id . date('His', strtotime($this->order->created_at)),
            'from' => $this->from,
            'to' => $this->to,
            'from_lat' => $this->from_lat,
            'from_long' => $this->from_long,
            'to_lat' => $this->to_lat,
            'to_long' => $this->to_long,
            'otp' => $this->otp ?? '',
            'status' => $this->status ?? 'pending',
            'order' => new OrderResource($this->order),
            'driver' => new DriverResource($this->driver),
            'logs' => $this->logs,
            'cancelled_by' => $this->cancelled_by,
            'delivered_at' => $this->delivered_at,
            'created_at' => $this->created_at,
            'createdAtStr' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
