<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorSettleResource extends JsonResource
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
            'vendorId' => $this->id,
            'totalOrders' => $this->orders[0]->total_orders ?? 0,
            'orderTotal' => ($this->orders[0]->total_amount) / 100 ?? 0,
            'gogoTotal' => ($this->orders[0]->total_to_pay) / 100 ?? 0,
            'businessName' => $this->business_name,
            'fullName' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'settlementTime' => $this->settlement_time,
            'lastSettledOn' => $this->settleLogs()->latest()->first() ? date_format($this->settleLogs()->latest()->first()->created_at, 'Y-m-d H:i:s') : 'No Logs, Registered: ' . $this->created_at,
            'vendoAdvanceBalance' => $this->vendorAdvanceSettlementBalance() ?? 0,
        ];
    }
}
