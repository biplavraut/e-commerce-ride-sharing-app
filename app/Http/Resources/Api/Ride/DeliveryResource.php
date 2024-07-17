<?php

namespace App\Http\Resources\Api\Ride;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $id = sprintf('%03d', $this->order_id);
        $orderId = "GGO" . date('Ymd', strtotime($this->created_at)) . "{$id}";
        return [
            'id' => $this->id,
            'orderNo' => $orderId,
            'from' => $this->from,
            'to' => $this->to,
            'fromLat' => $this->from_lat,
            'fromLong' => $this->from_long,
            'toLat' => $this->to_lat,
            'toLong' => $this->to_long,
            'otp' => $this->otp ?? '',
            'userOTP' => $this->user_otp ?? '',
            'status' => $this->status ?? 'pending',
            'paymentMode' => $this->order->payment_mode ?? "",
            'subTotal' => round($this->order->subtotal) ?? "",
            'total' => round($this->order->total) ?? "",
            'shippingFee' => round($this->order->shipping_fee) ?? "",
            // 'driver' => new DriverResource($this->driver),
            'created_at' => $this->created_at,
            'delivery_at' => $this->delivered_at != null ? date("F j, Y, g:i a", strtotime($this->delivered_at)) : "-",
            'assigned_at' => date("F j, Y, g:i a", strtotime($this->updated_at)),
            'createdAtStr' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
