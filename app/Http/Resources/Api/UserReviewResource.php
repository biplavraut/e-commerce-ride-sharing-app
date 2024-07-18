<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserReviewResource extends JsonResource
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
            'product' => $this->product()->select(['id', 'title'])->first(),
            'review' => $this->review ?? '',
            'likes' => $this->likes ?? 0,
            'rating' => $this->rating ?? 0,
            'images' => $this->images->map(function ($image) {
                return ['image' => $image->cropImage(150, 150), 'id' => $image->id];
            }),
            'reviewedOn' => $this->created_at->diffForHumans(),
        ];
    }
}
