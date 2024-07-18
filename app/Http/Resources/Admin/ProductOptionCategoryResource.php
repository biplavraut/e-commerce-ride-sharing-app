<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionCategoryResource extends JsonResource
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
            'products' => $this->productOptions()->count(),
            'created_at' => $this->created_at
        ];
    }
}
