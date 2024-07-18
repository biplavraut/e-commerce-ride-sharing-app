<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\VendorProductSubCategoryResource;

class VendorProductCategoryResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->image,
            'batch' => $this->batch,
            'order' => $this->order,
            'children' => VendorProductCategoryResource::collection($this->filteredCategory($request->vendor_id)),
            'productCount' => $this->products()->where('vendor_id', $request->vendor_id)->count()
        ];
    }
}
