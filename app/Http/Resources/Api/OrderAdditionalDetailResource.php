<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAdditionalDetailResource extends JsonResource
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
            'orderRefNo' => $this->order_ref_number,
            'promoCode' => $this->coupon_code,
            'promoDiscount' => $this->coupon_discount,
            'shippingCharge' => $this->shipping_charge,
            'gogoRewardRedeem' => $this->gogo_reward_redeem,
            'donation' => $this->donation,
            'cashback' => $this->order_cashback,
            'status' => $this->status,
            'orderTotal' => $this->order_total,
            'totalPaid' => $this->total_collected,
            'totalPayable' => (string) ($this->order_total - ($this->total_collected ?? 0)),
            'totalRefunded' => $this->total_refunded,
            'remarks' => $this->remarks
        ];
    }
}
