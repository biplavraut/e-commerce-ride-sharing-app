<?php

namespace App\Http\Resources\Api\Ride;

use App\Http\Resources\Api\VendorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            // 'orderNo'       =>  date('Ymd', strtotime($this->created_at)) . $this->id . date('His', strtotime($this->created_at)),
            'orderNo'       =>  $orderId,
            'subTotal' => $this->subtotal,
            'shippingCharge' => $this->shipping_fee,
            'total' => $this->total,
            'location' => $this->location,
            'location_area' => $this->delivery_location,
            'orderBy' => $this->order_by,
            'phone' => $this->phone,
            'paymentMode' => $this->payment_mode,
            'vendor' => new VendorResource($this->vendor)
        ];
    }
}
