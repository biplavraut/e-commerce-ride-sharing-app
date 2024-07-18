<?php

namespace App\Http\Resources\Api\Ride;

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
            'vehicle' => $this->vehicle_type,
            'otp' => $this->otp ?? '',
            'status' => $this->status ?? 'pending',
            'scheduled' => $this->schedule ? true : false,
            'scheduledDate' => $this->schedule ? $this->schedule->date : '',
            'scheduledTime' => $this->schedule ? $this->schedule->time : '',
            'bookedFor' => $this->booked_for ?? '',
            'bookedForNo' => $this->booked_for_no ?? '',
            'createdAt' => $this->created_at,
            'paid' => $this->done == 1,
            'surge' => $this->surge == 1,
            'donationAmount' => $this->donationAmount ?? 0,
            'createdAtStr' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
