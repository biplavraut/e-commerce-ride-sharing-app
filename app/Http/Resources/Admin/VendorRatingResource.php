<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorRatingResource extends JsonResource
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
            'user' => $this->user()->select(['first_name', 'last_name', 'phone', 'email'])->first(),
            'vendor' => $this->vendor()->select(['first_name', 'last_name', 'business_name', 'phone', 'email'])->first(),
            'review' => $this->review ?? '',
            'likes' => $this->likes ?? 0,
            'rating' => $this->rating ?? 0,
            'verified' => $this->verified == 1,
            'createdAt' => $this->created_at->diffForHumans(),
        ];
    }
}
