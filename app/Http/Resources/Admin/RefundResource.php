<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
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
            'refundAmt' => $this->refundable_amount,
            'total' => $this->total,
            'refNum' => $this->ref_number,
            'status' => $this->status,
            'vendor' => $this->vendor()->select(['business_name', 'phone', 'email'])->first(),
            'user' => $this->user()->select(['first_name', 'last_name', 'phone', 'email'])->first(),
            'created_at' => $this->created_at
        ];
    }
}
