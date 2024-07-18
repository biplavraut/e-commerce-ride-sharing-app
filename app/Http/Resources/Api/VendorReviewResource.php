<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorReviewResource extends JsonResource
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
            'user' => $this->anonymously  == 1 ? str_limit($this->user->first_name, 2) : $this->user->first_name,
            'isMine' => auth()->guard('api')->id() == $this->user_id,
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
