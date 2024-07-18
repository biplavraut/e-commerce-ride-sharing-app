<?php

namespace App\Http\Resources\Admin;

use App\VendorOption;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->vendorOptionCategory->id,
        //     'title' => $this->vendorOptionCategory->title,
        //     'status' => true
        // ];

        $vendorOptions = VendorOption::where('vendor_id', $this->vendor_id)->with('vendorOptionCategory')->get();
        $optionCategories = [];
        foreach ($vendorOptions as $key => $option) {
            $optionCategories[] = ['id' => $option->vendorOptionCategory->id, 'title' => $option->vendorOptionCategory->title, 'status' => true];
        }
        return $optionCategories;
    }
}
