<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RidersRevenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => array(
                "number" => $this->phone,
                "verified" => $this->phone_verified,
            ),
            'heard_from' => $this->from,
            'registered_at' => $this->created_at,
            'trips' => array(
                "total_trips" => $this->completed_trips[0]['total_trips'] ?? 0,
                "trip_revenue" => $this->completed_trips[0]['trip_revenue'] ?? 0,
            ),
            'gender' => $this->gender,
            'blocked' => $this->blocked,
            'reward_point' => $this->reward_point,
            'is_associated_rider' => $this->is_associated_rider
        ];
    }
}
