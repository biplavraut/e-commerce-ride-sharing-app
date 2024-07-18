<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVendorResource extends JsonResource
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
            'vendorId'    => $this->vendorId(),
            'businessName'  => $this->business_name,
            'email' => $this->email,
            'image' => $this->image,
            'image50'     => $this->cropImage(50, 50),
            'city' => $this->city,
            'address' => $this->address,
            'products' => VendorProductResource::collection($this->products()->Where('verified', 1)->limit(25)->get())
        ];
    }
}
