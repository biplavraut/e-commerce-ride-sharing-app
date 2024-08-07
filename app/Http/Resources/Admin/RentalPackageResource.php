<?php

namespace App\Http\Resources\Admin;

class RentalPackageResource extends CommonResource
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
            'name' => $this->name,
            'duration' => $this->duration,
            'distance' => $this->distance,
            'vehicles' => $this->vehicles,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
