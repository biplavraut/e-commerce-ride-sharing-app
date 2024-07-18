<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderOfferConfResource extends JsonResource
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
            'orderTitle' => $this->order_title,            
            'noOfOrders' => $this->no_of_orders,
            'discount'  => $this->discount,
            'from' => $this->from,
            'from_date' => Carbon::parse($this->from)->format('Y-m-d'),
            'from_time' => Carbon::parse($this->from)->format('H:i:s'),
            'to' => $this->to,
            'to_date' => Carbon::parse($this->to)->format('Y-m-d'),
            'to_time' => Carbon::parse($this->to)->format('H:i:s'),
            'enabled' => $this->enabled == 1
        ];
    }
}
