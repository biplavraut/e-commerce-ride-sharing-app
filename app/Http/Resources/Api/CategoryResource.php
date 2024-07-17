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
            'batch' => $this->batch,
            'image' => $this->image,
            'enabled' => $this->enabled,
            'subCategories' => CategoryResource::collection($this->childrenWithProductsOnly),
            // 'products' => CategoryProductResource::collection($this->products()->Where('verified', 1)->Where('hide', 0)->limit(25)->get())
        ];
    }
}
