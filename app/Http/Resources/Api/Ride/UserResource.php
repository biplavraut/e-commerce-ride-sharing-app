<?php

namespace App\Http\Resources\Api\Ride;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'    => $this->id,
            'userId' => $this->userId(),
            'image' => $this->image,
            'firstName'  => $this->first_name,
            'lastName'  => $this->last_name,
            'countryCode' => $this->country_code,
            'gender' => $this->gender ?? '',
            'phone' => $this->phone,
            'email' => $this->email,
            'emailVerified' => $this->isVerified(),
            'phoneVerified' => $this->isPhoneVerified(),
            'optPhone' => $this->phone1,
            'heardFrom' => $this->heard_from,
            'address' => $this->address,
            'myReferCode' => $this->refer_code ?? '',
            'rewardPoint' => $this->reward_point,
            'referCount' => $this->whoUsedMyCode()->count(),
            'usedCode' => $this->used_code,
            'blocked' => $this->blocked == 1,
            'elite' => $this->elite == 1,
            'gogoWallet' => $this->gogoWallet ? round($this->gogoWallet->amount) : 0,
            'recentLogin' => $this->last_login_at ? Carbon::parse($this->last_login_at)->diffForHumans() : 'N\\A',
            'totalSpentOnOrder' => $this->totalSpentOnOrder(),
            'registeredFrom' => $this->registered_from,
            'createdAt' => $this->created_at,
        ];
    }
}
