<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Vendor\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product' => new ProductResource($this->product),
            'name'          =>  $this->name,
            'price'         =>  $this->price,
            'discount_type' =>  $this->discount_type,
            'discount'      =>  $this->discount,
            'discountedPrice'    =>  $this->price - $this->discount,
            'quantity'      =>  $this->quantity,
            'color'         =>  $this->color,
            'size'          =>  $this->size
        ];
    }
}
