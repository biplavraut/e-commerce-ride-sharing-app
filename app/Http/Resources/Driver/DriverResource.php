<?php

namespace App\Http\Resources\Driver;

class DriverResource extends CommonResource
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
            'firstName'  => $this->first_name,
            'lastName'  => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'countryCode' => $this->country_code,
            'image' => $this->image,
            'heardFrom' => $this->heard_from,
            'address' => $this->address,
            'lat' => $this->lat,
            'long' => $this->long,
            'averageRating' => $this->averageRating() ?? 0,
            'verified' => $this->isVerified(),
            'isDocumentSubmitted' => $this->vehicleDetail ? true : false,
            'type' => $this->interested_in,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'subscription' => $this->subscription,
            'phoneVerified' => $this->isPhoneVerified(),
            'emailVerified' => $this->isEmailVerified(),
            $this->mergeWhen($this->token, [
                'accessToken' => $this->token,
                'tokenType' => 'bearer',
            ]),
        ];
    }
}
