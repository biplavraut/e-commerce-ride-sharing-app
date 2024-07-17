<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class OutstationTripResource extends JsonResource
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
            'tripId' => $this->tripId(),
            'package' => $this->package,
            'from' => $this->from,
            'fromLat' => $this->from_lat,
            'fromLong' => $this->from_long,
            'to' => $this->to,
            'toLat' => $this->to_lat,
            'toLong' => $this->to_long,
            'charge' => (int)$this->price,
            'paymentMode' => $this->payment_mode,
            'vehicle' => $this->vehicle_type,
            'status' => $this->status ?? 'pending',
            'startsAt' => date('d F, Y - h:i A', strtotime($this->starts_at)),
            'createdAt' => $this->created_at,
            'createdAtStr' => date('d F, Y - h:i A', strtotime($this->created_at)),
        ];
    }
}
