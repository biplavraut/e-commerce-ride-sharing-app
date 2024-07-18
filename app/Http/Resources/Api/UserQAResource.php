<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserQAResource extends JsonResource
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
            'question' => $this->question,
            'answer' => $this->answer ?? '',
            'questionedOn' => $this->created_at->diffForHumans(),
            'answeredOn' => $this->answer ? $this->updated_at->diffForHumans() : ''
        ];
    }
}
