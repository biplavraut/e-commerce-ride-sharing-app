<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResources extends JsonResource
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
            'id'  =>  $this->id,
            'product'  => $this->product_id ?  new CartProductResource($this->product) : null,
            'quantity' => $this->quantity,
            'size'  => $this->size ?? '',
            'color'  => $this->color ?? '',
            'date' => $this->date,
            'time' => $this->time,
            'specialInstruction' => $this->special_instruction
        ];
    }
}
