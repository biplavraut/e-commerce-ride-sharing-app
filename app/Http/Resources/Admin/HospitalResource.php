<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Api\VendorResource;
use App\Vendor;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalResource extends CommonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArrayWithoutNullValues($request)
    {
        $nearByVendor = json_decode($this->vendors);
        $vendorIds = array_column($nearByVendor, 'id');
        $vendor = Vendor::whereIn('id', $vendorIds)->get();
        $vendorDetails =  VendorResource::collection($vendor);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'lat' => $this->lat,
            'long' => $this->long,
            'vendors' => json_decode($this->vendors),
            'vendorDetails' => $vendorDetails,
            'createdAt' => $this->created_at
        ];
    }
}
