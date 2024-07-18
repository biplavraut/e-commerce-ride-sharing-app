<?php

namespace App\Http\Resources\Admin;

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
            'user' => $this->user()->select(['id', 'first_name', 'last_name', 'phone', 'email'])->first(),
            'product' => $this->product()->select(['id', 'title', 'code'])->first(),
            'question' => $this->question,
            'answer' => $this->answer,
            'createdAt' => $this->created_at
        ];
    }
}
