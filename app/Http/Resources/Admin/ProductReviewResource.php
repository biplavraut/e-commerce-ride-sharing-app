<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
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
            'user' => $this->user()->select(['id', 'first_name', 'last_name', 'phone', 'email'])->first(),
            'product' => $this->product()->select(['id', 'title','code'])->first(),
            'review' => $this->review,
            'rating' => $this->rating,
            'likes' => $this->likes,
            'images' => $this->images->map(function ($image) {
                return ['image150' => $image->cropImage(150, 150), 'image' => $image->image, 'id' => $image->id];
            }),
            'verified' => $this->isVerified(),
            'createdAt' => $this->created_at
        ];
    }
}
