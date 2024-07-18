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
        $id = sprintf('%03d', $this->id);
        $orderId = "GGO" . date('Ymd', strtotime($this->created_at)) . "{$id}";

        return [
            'id' => $this->id,
            'subTotal' => round($this->subtotal),
            'shippingCharge' => $this->shipping_fee,
            'refundAmt' => $this->refundable_amount,
            'total' => $this->total,
            'orderNo'       =>  $orderId,
            'refNum' => $this->ref_number,
            'paymentMode' => $this->payment_mode,
            'status' => $this->status,
            'vendor' => $this->vendor()->select(['business_name', 'phone', 'email'])->first(),
            'user' => $this->user()->select(['first_name', 'last_name', 'phone', 'email'])->first(),
            'created_at' => $this->created_at
        ];
    }
}
