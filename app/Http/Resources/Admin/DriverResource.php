<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'name' => $this->first_name . ' ' . $this->last_name,
            "image" => $this->image,
            "image50" => $this->cropImage(50, 50),
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'phoneVerified' => $this->phone_verified == 1,
            'emailVerified' => $this->email_verified == 1,
            'dob' => $this->dob,
            'address' => $this->address,
            'subscription' => $this->subscription,
            'interestedIn' => $this->interested_in,
            'regNumber' => $this->vehicleDetail->reg_no ?? '',
            'type' => $this->vehicleDetail->type ?? '',
            'manufacturingYear' => $this->vehicleDetail->manufacturing_year ?? '',
            'licenseCategory' => $this->vehicleDetail->license_category ?? '',
            'color' => $this->vehicleDetail->color ?? '',
            'licenseNo' => $this->vehicleDetail->license_no ?? '',
            'licenseExpiry' => $this->vehicleDetail->license_expiry ?? '',
            'bluebookExpiry' => $this->vehicleDetail->bluebook_expiry ?? '',
            'plateNo' => $this->vehicleDetail->plate_no ?? '',
            'license' => $this->vehicleDetail->license ?? '',
            'blueBook' => $this->vehicleDetail->blue_book ?? '',
            'blueBookSec' => $this->vehicleDetail->blue_book_sec ?? '',
            'blueBookTrd' => $this->vehicleDetail->blue_book_trd ?? '',
            'picture' => $this->vehicleDetail->picture ?? '',
            'ownerName' => $this->vehicleDetail->owner_name ?? '',
            'ownerContact' => $this->vehicleDetail->owner_contact ?? '',
            'verified' => $this->isVerified(),
            'partialStatus' => $this->isPartiallyVerified(),
            'createdAt' => $this->created_at,
            'stat' => $this->status ? $this->status->status : '-',
            'blocked' => $this->is_blocked == 1,
            'blacklisted' => $this->blacklisted,
            'totalAssigned' => $this->todayTotalAssigned()->count(),
            'totalDelivered' => $this->todayTotalDelivered()->count(),
            'is_associated' => $this->is_associated_rider,
            'junction' => $this->junction ? $this->junction->location : '',
            'documentState' => $this->documentState() == 0,
            'myReferCode' => $this->refer_code ?? '',
            'rewardPoint' => $this->reward_point,
            'referCount' => $this->whoUsedMyCode()->count(),
            'usedCode' => $this->used_code,
            'from' => $this->from,
        ];
    }
}
