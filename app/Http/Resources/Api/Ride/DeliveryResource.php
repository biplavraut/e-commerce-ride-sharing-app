<?php

namespace App\Http\Resources\Api\Ride;

use App\Http\Resources\Api\OrderAdditionalDetailResource;
use App\Order;
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
            'vendor' => $this->order->vendor()->select(['business_name', 'phone', 'city', 'address'])->first(),
            'status' => $this->status ?? 'pending',
            'paymentMode' => $this->order->payment_mode ?? "",
            'subTotal' => round($this->order->subtotal) ?? "",
            'total' => round($this->order->total) ?? "",
            'shippingFee' => round($this->order->shipping_fee) ?? "",
            'nearestLandMark' => $this->order->nearest_landmark ?? '',
            'specialInstruction' => $this->order->special_instruction ?? '',
            'alternateName' => $this->order->alternate_name ?? '',
            'alternatePhone' => $this->order->alternate_phone ?? '',
            'totalItems' => $this->order->orderItems()->count(),
            // 'driver' => new DriverResource($this->driver),
            'created_at' => $this->created_at,
            'delivery_at' => $this->order->date != null ? date("F j, Y, g:i a", strtotime($this->order->date)) : "-",
            // 'delivery_at' => $this->delivered_at != null ? date("F j, Y, g:i a", strtotime($this->delivered_at)) : "-",
            'assigned_at' => date("F j, Y, g:i a", strtotime($this->created_at)),
            'createdAtStr' => date('d F, Y - h:i A', strtotime($this->created_at)),
            'additionalDetail' =>  new OrderAdditionalDetailResource($this->order->additionalDetails),
            'countOrderRef' => Order::where('ref_number', $this->order->ref_number)->count(),
        ];
    }
}
