<?php

namespace App\Http\Resources\Admin;

class RidingFareResource extends CommonResource
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
            'vehicle' => $this->vehicle,
            'price' => $this->price,
            'flat_price' => $this->flat_price,
            'night_surge' => $this->night_surge,
            'description' => $this->description,
            'surges' => RidingPriceSurgeResource::collection($this->surges),
            'timeSurges' => $this->surges()->count(),
            'created_at' => $this->created_at
        ];
    }
}
