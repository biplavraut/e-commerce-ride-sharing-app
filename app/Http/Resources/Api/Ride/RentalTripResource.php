<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class RentalTripResource extends JsonResource
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
            'package' => $this->package()->select(['name', 'distance', 'duration'])->first(),
            'from' => $this->from,
            'fromLat' => $this->from_lat,
            'fromLong' => $this->from_long,
            'charge' => (int)$this->price,
            'paymentMode' => $this->payment_mode,
            'vehicle' => $this->vehicle_type,
            'status' => $this->status ?? 'pending',
            'user' => $this->user()->select(['id', 'first_name as firstName', 'last_name as lastName', 'phone', 'image'])->first(),
            'driver' => $this->driver ? $this->driver()->select(['id', 'first_name as firstName', 'last_name as lastName', 'phone', 'image'])->first(): nullValue(),
            'createdAt' => $this->created_at,
            'createdAtStr' => date('d F, Y - h:i A', strtotime($this->created_at)),
            'startsAt' => date('d F, Y - h:i A', strtotime($this->starts_at)),
            'endsAt' => date('d F, Y - h:i A', strtotime($this->ends_at)),
        ];
    }
}
