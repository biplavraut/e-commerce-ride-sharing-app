<?php

namespace App\Http\Resources\Vendor;

class AdminResource extends CommonResource
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
            'name'  => $this->business_name,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'lat' => $this->lat,
            'long' => $this->long,
            'country_code' => $this->country_code,
            'type'  => $this->type,
            'image' => $this->image,
            'verified' => $this->isVerified(),
            'phone_verified' => $this->isPhoneVerified()
        ];
    }
}
