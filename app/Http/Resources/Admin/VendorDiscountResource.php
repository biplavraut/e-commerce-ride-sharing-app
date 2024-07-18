<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorDiscountResource extends JsonResource
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
            'id'  =>  $this->id,
            'discount' => $this->discount,
            'status' => $this->status,
            'vendorId' => $this->vendor->id,
            'name' => $this->vendor->business_name,
            'phone' => $this->vendor->phone,
            'image50'     => $this->vendor->cropImage(50, 50),
            'created_at' => $this->created_at
        ];
    }
}
