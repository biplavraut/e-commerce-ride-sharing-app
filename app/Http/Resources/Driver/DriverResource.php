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
            'subscription' => $this->packages()->count() > 0 ? $this->currentPackage()->name : $this->subscription,
            'phoneVerified' => $this->isPhoneVerified(),
            'emailVerified' => $this->isEmailVerified(),
            'district' =>  $this->myAddress('district'),
            'municipality' => $this->myAddress('municipality'),
            'ward' => (int)$this->myAddress('ward'),
            'myReferCode' => $this->refer_code ?? '',
            'rewardPoint' => $this->reward_point,
            'referCount' => $this->whoUsedMyCode()->count(),
            'usedCode' => $this->used_code ?? '',
            $this->mergeWhen($this->token, [
                'accessToken' => $this->token,
                'tokenType' => 'bearer',
            ]),
        ];
    }
}
