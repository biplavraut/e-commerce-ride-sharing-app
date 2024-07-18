<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class AdditionServiceResource extends JsonResource
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
            'subtitle' => $this->subtitle ?? '',
            'cashback' => $this->cashback,
            'enabledPromo' => $this->enabled_promo,
            'image' => Str::contains($this->image, 'no-image') ? '' : $this->image,
        ];
    }
}
