<?php

namespace App\Http\Resources\Api;

use App\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
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
            'id' => $this['id'],
            'vendor' => $this['vendor_id'] != null ? new VendorResource(Vendor::find($this['vendor_id'])) : nullValue(),
            'name' => $this['name'],
            'image' => $this['image'],
            'order' => $this['order'],
            'fixed' => Carbon::parse($this['expire_in'])->gte(Carbon::now()) ? true : false,
            'hasBranches' => $this->has_branches == 1
        ];
    }
}
