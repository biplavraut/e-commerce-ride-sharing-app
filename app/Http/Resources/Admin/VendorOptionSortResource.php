<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOptionSortResource extends JsonResource
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
            'category' => $this->vendorOptionCategory()->select(['id','title', 'layout'])->first(),
            'vendor' => $this->vendor()->select(['id','business_name as businessName'])->first()
        ];
    }
}
