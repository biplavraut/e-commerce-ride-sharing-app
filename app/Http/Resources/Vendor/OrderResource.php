<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Admin\DeliveryResource;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Vendor\OrderItemResource;
use App\Http\Resources\Vendor\VendorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Delivery;
use App\Http\Resources\Api\OrderAdditionalDetailResource;
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
        $id = sprintf('%03d', $this->id);
        $orderId = "GGO" . date('Ymd', strtotime($this->created_at)) . "{$id}";

        $otp = '';

        if ($this->takeaway == 1) {
            $otp = $this->otp;
        } else {
            $delivery = Delivery::select('otp')->where('order_id', $this->id)->first();
            if ($delivery) {
                $otp = $delivery->otp;
            }
        }

        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'sub_total' => round($this->subtotal),
            'shipping_charge' => $this->shipping_fee,
            'total' => round($this->total - $this->shipping_fee),
            'location' => $this->location,
            'location_area' => $this->delivery_location,
            'lat' => $this->lat,
            'long' => $this->long,
            'order_by' => $this->order_by,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'preferred_deli_time' => $this->preferred_deli_time,
            'preferred_deli_date' => $this->preferred_deli_date,
            'delivery_date' => $this->date ? date('M d, h:i a', strtotime($this->date)) : '-',
            'ordered_on' => $this->created_at,
            'payment_mode' => $this->payment_mode,
            'items' => OrderItemResource::collection($this->orderItems),
            'auth' => auth()->id() ?? "",
            'accepted' => $this->accepted == 1,
            'delivery' => new DeliveryResource($this->deliveryRequest),
            'orderNo'       =>  $orderId,
            'refNumber'       =>  $this->ref_number,
            'agoTime'   => $this->created_at->diffForHumans(),
            'dynamicColor' => substr($this->dynaicColor($this->created_at), -7),
            'vendor' => new VendorResource($this->vendor),
            'refundableAmount' => $this->refundable_amount,
            'nearestLandmark' => $this->nearest_landmark,
            'specialInstruction' => $this->special_instruction,
            'alternatePhone' => $this->alternate_phone,
            'alternateName' => $this->alternate_name,
            'takeaway' => $this->takeaway == 1,
            'otp'    =>  is_numeric($otp) ? $otp : 'Pending',
            'additionalDetail' =>  new OrderAdditionalDetailResource($this->additionalDetails),
            'countOrderRef' => Order::where('ref_number', $this->ref_number)->count(),
            'type' => $this->vendor->has_service(41) ? 'service' : 'product'
        ];
    }

    private function dynaicColor($carbonObject)
    {
        return str_ireplace(
            [' seconds ago', ' second ago', ' minutes ago', ' minute ago', ' hours ago', ' hour ago', ' days ago', ' day ago', ' weeks ago', ' week ago'],
            ['#32b332', '#27c427', '#97d93b', '#99ad32', '#c4a829', '#ebc413', '#bd923c', '#eba721', '#c25d32', '#eba721'],
            $carbonObject->diffForHumans()
        );
    }
}
