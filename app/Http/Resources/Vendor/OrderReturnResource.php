<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Admin\UserResource;
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
            'returnQuantity' => $this->quantity,
            'status' => $this->status,
            'user' => new UserResource($this->user),
            'product' => new ProductResource($this->product),
            'items' => new OrderItemResource($this->orderItem),
            'order' => new OrderResource($this->order),
            'requestedAt'   => $this->created_at->diffForHumans(),
            'remarks' => $this->remarks,
            'createdAt' => date('M d, h:i a', strtotime($this->created_at))
        ];
    }
}
