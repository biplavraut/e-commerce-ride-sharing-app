<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'batch' => $this->batch ?? '',
            'image' => $this->image,
            'enabled' => $this->enabled ?? false,
			'showCategories' => $this->show_product_category,
            'productCount' => $this->products()->count(),
            'subCategories' => CategoryResource::collection($this->childrenWithProductsOnly),
        ];
    }
}
