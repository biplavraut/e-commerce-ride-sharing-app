<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Admin\VendorOpeningTimeResource;

class VendorResource extends CommonResource
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
            'vendorId' => $this->vendorId(),
            'business_name'  => $this->business_name,
            'first_name'  => $this->first_name,
            'last_name'  => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country_code' => $this->country_code,
            'partnership_type'  => $this->partnership_type,
            'type'  => $this->type,
            'heard_from'  => $this->heard_from,
            'image' => $this->image,
            'image50'     => $this->cropImage(50, 50),
            'city' => $this->city,
            'address' => $this->address,
            'area' => $this->area ?? '',
            'lat' => $this->lat,
            'long' => $this->long,
            'verified' => $this->isVerified(),
            'phone_verified' => $this->isPhoneVerified(),
            'email_verified' => $this->isEmailVerified(),
            "type" =>  "vendor",
            "hide" =>  $this->is_hidden == 1 ? true : false,
            "enable" =>  $this->status == 1 ? true : false,
            "openingTimeForm" => new VendorOpeningTimeResource($this->scheduleTime),
            'settlement_time' => $this->settlement_time,
            'lat' => $this->lat,
            'long' => $this->long,
            'serviceList' => $this->services ? $this->services()->pluck('name') : null,
            'products' => $this->products()->count(),
            $this->mergeWhen($this->token, [
                'accessToken' => $this->token,
                'tokenType' => 'bearer',
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
