<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorSettleResource extends JsonResource
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
            'vendorId' => $this->vendorId(),
            'businessName'  => $this->business_name,
            'fullName'  => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->country_code . '' . $this->phone,
            'orderTotalCOD' => $this->orderTotalCOD(),
            'orderTotalDIGITAL' => $this->orderTotalDIGITAL(),
            'count' => $this->countOrder(),
            'fromTo' => $this->fromTo()
        ];
    }
}
