<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class GlobalNotifcationResource extends JsonResource
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
            'title' => $this->title,
            'message' => $this->message,
            'image' => Str::contains($this->image, 'no-image') ? '': $this->image,
            'isGlobal' => true,
            'createdAt' => $this->updated_at->diffForHumans()
        ];
    }
}
