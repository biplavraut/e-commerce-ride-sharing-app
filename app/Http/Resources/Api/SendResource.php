<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\UserResource;

class SendResource extends JsonResource
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
            'id'  =>  $this->id,
            'uniqueId'  =>  "GGS" . date("Ymd") . "000" . $this->id,
            'user' => $this->user ?  new UserResource($this->user) : null,
            'user_id' => $this->user_id,
            'pickup_location_name' => $this->pickup_location_name,
            'delivery_location_name' => $this->delivery_location_name,
            'pickup_location_lat' => $this->pickup_location_lat,
            'pickup_location_long' => $this->pickup_location_long,
            'delivery_destination_lat' => $this->delivery_destination_lat,
            'delivery_destination_long' => $this->delivery_destination_long,
            'distance_in_km' => $this->distance_in_km,
            'delivery_item_type' => $this->item,
            'delivery_item_weight' => $this->delivery_item_weight,
            'moneytery_value_of_item' => $this->moneytery_value_of_item,
            'generated_total_price' => $this->generated_total_price,
            'discount_price' => $this->discount_price,
            'discount_method' => $this->discount_method,
            'net_total_price' => $this->net_total_price,
            'pickup_person_name' => $this->pickup_person_name,
            'pickup_point_address' => (string)$this->pickup_point_address,
            'pickup_person_number' => $this->pickup_person_number,
            'pickup_date' => $this->pickup_date,
            'pickup_time' => $this->pickup_time,
            'pickup_comment' => $this->pickup_comment,
            'contact_person_name' => $this->contact_person_name,
            'contact_person_address' => $this->contact_person_address,
            'contact_person_number' => $this->contact_person_number,
            'delivery_date' => $this->delivery_date,
            'delivery_time' => $this->delivery_time,
            'delivery_comment' => $this->delivery_comment,
            'notify_receipents_by_sms' => $this->notify_receipents_by_sms,
            'status' => $this->status,
            'extra_column' => unserialize($this->extra_column),
            'dynamicColor' => substr($this->dynaicColor($this->created_at), -7),
            'agoTime'   => $this->created_at->diffForHumans(),
            'pickUpOtp' => $this->pickup_otp,
            'deliveryOtp' => $this->delivery_otp
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
