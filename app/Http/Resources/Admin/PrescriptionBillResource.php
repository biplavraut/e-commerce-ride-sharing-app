<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Api\VendorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionBillResource extends JsonResource
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
            'image' =>  $this->image,
            'type' => $this->type,
            'vendor' => $this->type === 'vendor' ? new VendorResource($this->vendor) : json_decode('{}'),
            'otherVendor' => $this->vendor_name,
            'billAmount' => $this->bill_amount,
            'created_at' => $this->created_at
        ];
    }
}
