<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'id'    => $this->id,
            'firstName'  => $this->first_name,
            'lastName'  => $this->last_name,
            'phone' => $this->phone,
            'countryCode' => $this->country_code,
            'image' => $this->image,
            'averageRating' => $this->averageRating() ?? 0,
            'phoneVerified' => $this->isPhoneVerified(),
            'vehicleType' => $this->vehicleDetail->type ?? '',
            'vehicleNo' => $this->vehicleDetail->plate_no ?? '',
            'color' => $this->vehicleDetail->color ?? '',
            'totalCompletedTrips' => $this->completedTrips->count(),
            'vehicleImage' => $this->vehicleDetail->picture ?? '',
            'isDocumentSubmitted' => $this->vehicleDetail ? true : false,
            'type' => $this->interested_in
        ];
    }
}
