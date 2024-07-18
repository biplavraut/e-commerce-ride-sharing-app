<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TopUserTransactionResource extends JsonResource
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
            'phone' => $this->phone,
            'registered_at' => $this->created_at,
            'orders' => $this->orders[0]['total_orders'] ?? 0,
            'trips' => $this->trips[0]['total_trips'] ?? 0,
            'utility' => $this->transactionHistories[0]['utilities'] ?? 0,
            'total' => ($this->orders[0]['total_orders'] ?? 0) +  ($this->trips[0]['total_trips'] ?? 0) + ($this->transactionHistories[0]['utilities'] ?? 0)
        ];
    }
}
