<?php

namespace App\Http\Resources\Admin;

class PremiumPlaceResource extends CommonResource
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
            'image'       => $this->imageUrl(),
            'image50'     => $this->cropImage(50, 50),
            'image150'    => $this->cropImage(150, 150),
            'location' => $this->location,
            'lat' => $this->lat,
            'long' => $this->long,
            'price' => $this->price,
            'radius' => $this->radius,
            'popular' => $this->popular == 1,
            'hide' => $this->hide == 1,
            'created_at' => $this->created_at
        ];
    }
}
