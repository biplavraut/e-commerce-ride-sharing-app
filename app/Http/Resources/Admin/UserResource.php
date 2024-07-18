<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Api\UserAddressResource;

class UserResource extends CommonResource
{
    private $accessToken;

    public function __construct($resource, $accessToken = '')
    {
        parent::__construct($resource);
        $this->accessToken = $accessToken;
    }

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
            'email' => $this->email ?? '',
            'countryCode' => $this->country_code,
            'dob' => $this->dob ?? '',
            'gender' => $this->gender ?? '',
            'phone' => $this->phone,
            'optPhone' => $this->phone1,
            'headFrom' => $this->heard_from,
            'office' => $this->office ?? '',
            'address' => $this->address ?? '',
            'lat' => $this->lat ?? '',
            'long' => $this->long ?? '',
            'verified' => $this->isVerified(),
            'phoneVerified' => $this->isPhoneVerified(),
            'image'    => $this->imageUrl(),
            'image50'  => $this->cropImage(50, 50),
            'image150' => $this->cropImage(150, 150),
            'rideSoFar' => round($this->completedTrips->sum('distance1')),
            'totalSaved' => $this->totalSaved(),
            'myReferCode' => $this->refer_code ?? '',
            'rewardPoint' => $this->reward_point,
            'referCount' => $this->whoUsedMyCode()->count(),
            'gogoWallet' => $this->gogoWallet ? round($this->gogoWallet->amount) : 0,
            'blocked' => $this->blocked == 1,
            'eliteUser' => $this->elite == 1,
            'homeResident' => $this->myAddress ? new UserAddressResource($this->myAddress) : nullValue(),
            $this->mergeWhen($this->accessToken, [
                'accessToken' => $this->accessToken,
                'tokenType' => 'bearer',
            ]),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     *
     * @return void
     */
    public function withResponse($request, $response)
    {
        if ($this->accessToken) {
            $response->withHeaders([
                'X-Access-Token'     => $this->accessToken,
                'X-Token-Type'       => 'bearer',
                'X-Token-Expires-In' => auth()->guard('api')->factory()->getTTL() * 60,
            ]);
        }
    }
}
