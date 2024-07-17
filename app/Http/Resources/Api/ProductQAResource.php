<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductQAResource extends JsonResource
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
            'isMine' => auth()->guard('api')->id() == $this->user_id,
            'user' => $this->user->first_name,
            'question' => $this->question,
            'answer' => $this->answer ?? '',
            'questionedOn' => $this->created_at->diffForHumans(),
            'answeredOn' => $this->answer ? $this->updated_at->diffForHumans() : ''
        ];
    }
}
