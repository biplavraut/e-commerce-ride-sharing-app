<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Delivery;
use App\Order;

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
        $otp = '';

        if ($this->takeaway == 1) {
            $otp = $this->otp;
        } else {
            $delivery = Delivery::select('user_otp')->where('order_id', $this->id)->first();
            if ($delivery) {
                $otp = $delivery->user_otp;
            }
        }


        return [
            'id'            =>  $this->id,
            'orderNo'       =>  $this->orderNo(),
            'orderedOn' =>  date('M d, h:i a', strtotime($this->created_at)),
            'deliveryDate' =>   $this->status == "DELIVERED" ? date('M d, h:i a', strtotime($this->updated_at)) : date('M d, h:i a', strtotime($this->date)),
            'orderItems'    =>  OrderItemResource::collection($this->orderItems),
            'orderBy'       =>  $this->order_by,
            'phone'         =>  $this->phone,
            'email'         =>  $this->email,
            'location'      =>  $this->location,
            'location_area' =>  $this->delivery_location,
            'latitude'      =>  $this->lat,
            'longitude'     =>  $this->long,
            'subtotal'      =>  round($this->subtotal),
            'shippingFee'   =>  $this->shipping_fee,
            'total'         =>  round($this->total),
            'paymentType'   =>  $this->payment_mode,
            'status'        =>  $this->status,
            'takeaway'        =>  $this->takeaway,
            'refundableAmount' => round($this->refundable_amount),
            'nearestLandmark' => $this->nearest_landmark,
            'specialInstruction' => $this->special_instruction,
            'reason' => $this->reason ?? '',
            'otp'           =>  $otp ?? '',
            'feedback' =>   $this->orderFeedbacks()->where('order_id', $this->id)->where('user_id', auth()->guard('api')->id())->first() ? new OrderFeedbackResource($this->orderFeedbacks()->where('order_id', $this->id)->where('user_id', auth()->guard('api')->id())->first()) : nullValue(),
            'additionalDetail' => new OrderAdditionalDetailResource($this->additionalDetails),
            'countOrderRef' => Order::where('ref_number', $this->ref_number)->count(),
        ];
    }
}
