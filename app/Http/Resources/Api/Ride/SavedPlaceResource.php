<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class SavedPlaceResource extends JsonResource
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
            'name' => $this->name,
            'location' => $this->location,
            'latitude' => $this->lat,
            'longitude' => $this->long,
            'flag' => $this->flag
        ];
    }
}
