<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class RidingFareResource extends JsonResource
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
            'vehicle' => $this->vehicle,
            'price' => $this->price,
            'flatPrice' => $this->flat_price,
            'nightSurge' => $this->night_surge ?? 1,
            'addonSurge' => $this->addonSurge == 0 ? 1 : $this->addonSurge,
            'finalPrice' => $this->finalFare($this->addonSurge ?? 0),
            'finalLessPrice' => $this->finalLessFare($this->addonSurge ?? 0),
            'description' => $this->when($this->description, $this->description),
        ];
    }
}
