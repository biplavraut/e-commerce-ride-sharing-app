<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorDetailResource extends CommonResource
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
            'area' => $this->area,
            'lat' => $this->lat,
            'long' => $this->long,
            'from' => $this->from,
            'verified' => $this->isVerified(),
            'phone_verified' => $this->isPhoneVerified(),
            'email_verified' => $this->isEmailVerified(),
            "hide" =>  $this->is_hidden == 1 ? true : false,
            "enable" =>  $this->status == 1 ? true : false,
            "order_offer_applicable" => $this->order_offer_applicable == 1 ? true : false,
            "openingTimeForm" => new VendorOpeningTimeResource($this->scheduleTime),
            'settlement_time' => $this->settlement_time,
            'lat' => $this->lat,
            'long' => $this->long,
            'radiusLimit' => $this->radius_limit,
            'serviceList' => $this->services ? $this->services()->pluck('name') : null,
            'serviceSlug' => $this->services ? $this->services()->pluck('slug') : null,
            'products' => $this->products()->count(),
            'vendorOptionCategories' => VendorOptionResource::collection($this->vendorOptions),
            'takeaway' => $this->takeaway == 1,
            'dine_in' => $this->dine_in == 1,
            'created_at' => $this->created_at,
        ];
    }
}
