<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderReturnResource extends JsonResource
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
            'ticket' => $this->ticket,
            'reason' => $this->reason,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'user' => $this->user,
            'vendor' => $this->vendor,
            'product' => $this->product,
            'order' => $this->order,
            'orderItem' => $this->orderItem,
            'remarks' => $this->remarks,
            'createdAt' => date('M d, h:i a', strtotime($this->created_at))
        ];
    }
}
