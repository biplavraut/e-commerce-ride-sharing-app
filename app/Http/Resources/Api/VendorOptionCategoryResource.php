<?php

namespace App\Http\Resources\Api;

use App\Vendor;
use App\Product;
use App\VendorOption;
use App\ProductOption;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorOptionCategoryResource extends JsonResource
{
    protected $category;

    public function category($value)
    {
        $this->category =   $value;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vendorIds     =    VendorOption::where('service_id', $request->category_id)->Where('vendor_option_category_id', $this->id)->orderBy('order')->pluck('vendor_id')->toArray();
        $orderedIds = implode(',', $vendorIds);

        $vendors = Vendor::whereIn('id', $vendorIds)->where('verified', 1)->Where('status', 1)->orderByRaw("FIELD(id, $orderedIds)")->limit(10)->get()->filter(function ($model) use ($request) {
            return $model->with_in_radius($request->lat, $request->long) == true;
        });
        return [
            'id'    =>  $this->id,
            'title' =>  $this->title,
            'slug'  =>  $this->slug,
            'layout'  =>  $this->layout ?? 0,
            'vendors'  => VendorResource::collection($vendors)
        ];
    }
}
