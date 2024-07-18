<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Admin\VendorOpeningTimeResource;

class VendorLessResource extends CommonResource
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
            'id'    => $this->id,
            'business_name'  => $this->business_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country_code' => $this->country_code,
            'image' => $this->image,
            'image50'     => $this->cropImage(50, 50),
            'city' => $this->city,
            'address' => $this->address,
            "openingTimeForm" => new VendorOpeningTimeResource($this->scheduleTime),
        ];
    }
}
