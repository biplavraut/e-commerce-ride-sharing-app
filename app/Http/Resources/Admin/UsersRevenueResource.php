<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersRevenueResource extends JsonResource
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
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => array(
                "number" => $this->phone,
                "verified" => $this->phone_verified,
            ),
            'heard_from' => $this->heard_from,
            'registered_at' => $this->created_at,
            // 'orders_yesterday' => array(
            //     "total_orders" => $this->orders_yesterday[0]['total_orders'] ?? 0,
            //     "order_revenue" => $this->orders_yesterday[0]['order_revenue'] ?? 0,
            // ),  
            // 'orders_today' => array(
            //     "total_orders" => $this->orders_today[0]['total_orders'] ?? 0,
            //     "order_revenue" => $this->orders_today[0]['order_revenue'] ?? 0,
            // ),  
            // 'orders_this_week' => array(
            //     "total_orders" => $this->orders_this_week[0]['total_orders'] ?? 0,
            //     "order_revenue" => $this->orders_this_week[0]['order_revenue'] ?? 0,
            // ),  
            'orders' => array(
                "total_orders" => $this->orders[0]['total_orders'] ?? 0,
                "order_revenue" => $this->orders[0]['order_revenue'] ?? 0,
            ),            
            // 'trips_yesterday' => array(
            //     "total_trips" => $this->trips_yesterday[0]['total_trips'] ?? 0,
            //     "trip_revenue" => $this->trips_yesterday[0]['trip_revenue'] ?? 0,
            // ),
            // 'trips_today' => array(
            //     "total_trips" => $this->trips_today[0]['total_trips'] ?? 0,
            //     "trip_revenue" => $this->trips_today[0]['trip_revenue'] ?? 0,
            // ),
            
            // 'trips_this_week' => array(
            //     "total_trips" => $this->trips_weekly[0]['total_trips'] ?? 0,
            //     "trip_revenue" => $this->trips_weekly[0]['trip_revenue'] ?? 0,
            // ),
            'trips' => array(
                "total_trips" => $this->trips[0]['total_trips'] ?? 0,
                "trip_revenue" => $this->trips[0]['trip_revenue'] ?? 0,
            ),
            'gender' => $this->gender,
            'blocked' => $this->blocked,
            'reward_point' => $this->reward_point,
            'last_login_at' => $this->last_login_at
        ];
    }
}
