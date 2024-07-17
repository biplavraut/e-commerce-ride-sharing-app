<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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
            'from' => $this->from,
            'to' => $this->to,
            'fromLat' => $this->from_lat,
            'fromLong' => $this->from_long,
            'toLat' => $this->to_lat,
            'toLong' => $this->to_long,
            'charge' => (int)$this->price,
            'paymentMode' => $this->payment_mode,
            'distance' => $this->distance ?? '',
            'duration' => $this->duration ?? '',
            'otp' => $this->otp ?? '',
            'status' => $this->status ?? 'pending',
            'user' => new UserResource($this->user),
            'rider' => new DriverResource($this->driver),
            'distance' => $this->distance,
            'duration' => $this->duration,
            'dispute' => $this->dispute,
            'ref_number' => $this->ref_number,
            'log' => $this->status == "cancelled" ? $this->logs : '',
            'cancelledBy' => ($this->status == "cancelled" || $this->dispute != null) ? $this->cancelled_by : "-",
            'completedAt' => $this->completed_at,
            'schedule' => $this->status == "scheduled" ? $this->schedule : null,
            'createdAt' => $this->created_at
        ];
    }
}
