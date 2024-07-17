<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Delivery;

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
        $otp = Delivery::select('user_otp')->where('order_id', $this->id)->first();
        return [
            'id'            =>  $this->id,
            'orderNo'       =>  $orderId,
            // 'orderNo'       =>  date('Ymd', strtotime($this->created_at)) . $this->id . date('His', strtotime($this->created_at)),
            'orderedOn' =>  date('M d, h:i a', strtotime($this->created_at)),
            'deliveryDate' =>   $this->status == "DELIVERED" ? date('M d, h:i a', strtotime($this->updated_at)) : '-',
            'orderItems'    =>  OrderItemResource::collection($this->orderItems),
            'orderBy'       =>  $this->order_by,
            'phone'         =>  $this->phone,
            'email'         =>  $this->email,
            'location'      =>  $this->location,
            'location_area' =>  $this->delivery_location,
            'latitude'      =>  $this->lat,
            'longitude'     =>  $this->long,
            'subtotal'      =>  $this->subtotal,
            'shippingFee'   =>  $this->shipping_fee,
            'total'         =>  $this->total,
            'paymentType'   =>  $this->payment_mode,
            'status'        =>  $this->status,
            'refundableAmount' => $this->refundable_amount,
            'reason' => $this->reason ?? '',
            'otp'           =>  !empty($otp) ? $otp['user_otp'] : "",
        ];
    }
}
