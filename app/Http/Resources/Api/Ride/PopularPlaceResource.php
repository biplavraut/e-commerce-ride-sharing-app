<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class PopularPlaceResource extends JsonResource
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
            'image'       => $this->imageUrl(),
            'image50'     => $this->cropImage(50, 50),
            'image150'    => $this->cropImage(150, 150),
            'location' => $this->location,
            'lat' => $this->lat,
            'long' => $this->long,
            'price' => $this->price,
            'radius' => $this->radius,
        ];
    }
}
