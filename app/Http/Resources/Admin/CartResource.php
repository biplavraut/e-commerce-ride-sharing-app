<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'product'  => $this->product_id ?  new ProductResource($this->product) : null,
            'quantity' => $this->quantity,
            'size'  => $this->size ?? '',
            'color'  => $this->color ?? '',
            'date' => $this->date,
            'time' => $this->time,
            'specialInstruction' => $this->special_instruction
        ];
    }
}
