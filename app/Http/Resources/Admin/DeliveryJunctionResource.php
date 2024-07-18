<?php

namespace App\Http\Resources\Admin;

class DeliveryJunctionResource extends CommonResource
{
    /**
     * Transform the resource into an array by changing null values to empty string.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArrayWithoutNullValues($request)
    {
        return [
            'id' => $this->id,
            'location' => $this->location,
            'lat' => $this->lat,
            'long' => $this->long,
            'created_at' => $this->created_at
        ];
    }
}
