<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PoolResource extends JsonResource
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
            'id'  =>  $this->id,
            'user_id' =>  $this->user_id,
            'current_location' =>  $this->current_location,
            'desire_destination' => $this->desire_destination,
            'location_lat' => (string) $this->location_lat,
            'location_long'  => (string) $this->location_long,
            'destination_lat' => (string) $this->destination_lat,
            'destination_long' => (string) $this->destination_long,
            'date' => $this->date,
            'time' => $this->time,
            'distance_in_km' => $this->distance_in_km,
            'required_seat' => $this->required_seat,
            'vechical_type' => $this->vechical_type,
            'is_recurring' => $this->is_recurring,
            'recurring_strat_date' => $this->recurring_strat_date,
            'recurring_end_date' => $this->recurring_end_date,
            'pool_type' => $this->pool_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'vehicles' => $this->vehicle
        ];
    }
}
