<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOptionCategoryResource extends JsonResource
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
            'id'    =>  $this->id,
            'title' =>  $this->title,
            'slug'  =>  $this->slug,
            'layout'  =>  $this->layout,
            'service' => $this->service,
            'titleColor' => $this->title_color,
            'layoutColor' => $this->layout_color,
            'vendors' => $this->vendorOptions()->count(),
            'created_at' => $this->created_at
        ];
    }
}
