<?php

namespace App\Http\Resources\Api\Redis;

use Illuminate\Http\Resources\Json\JsonResource;

class RedisCategoryResource extends JsonResource
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
            'productCount' => $this->productCount,
            'subCategories' => $this->subCategories,
        ];
    }
}
