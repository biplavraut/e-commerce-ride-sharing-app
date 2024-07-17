<?php

namespace App\Http\Resources\Admin;

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
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country_code' => $this->country_code,
            'type'  => $this->type,
            'image' => $this->image,
            'verified' => $this->isVerified(),
            'phone_verified' => $this->isPhoneVerified(),
            'created_at' => $this->created_at
        ];
    }
}
