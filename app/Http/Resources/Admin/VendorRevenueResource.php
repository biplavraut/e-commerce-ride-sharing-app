<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorRevenueResource extends JsonResource
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
            'vendorId' => $this->vendorId(),
            'businessName'  => $this->business_name,
            'fullName'  => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->country_code . '' . $this->phone,
            'settlementTime' => $this->settlement_time,
            'orderTotalCOD' => round($this->orderTotalCOD()),
            'orderTotalDIGITAL' => round($this->orderTotalDIGITAL()),
            'orderTotalWallet' => round($this->orderTotalWallet()),
            'count' => $this->countOrder(),
            'lastSettledOn' => $this->settleLogs()->latest()->first() ? $this->settleLogs()->latest()->first()->created_at : '',
            'fromTo' => $this->fromTo(),
            'registered_at' => $this->created_at,
            'orders_monthly' => array(
                "total_orders" => $this->orders[0]['total_orders'] ?? 0,
                "order_revenue" => $this->orders[0]['order_revenue'] ?? 0,
            ),
            'serviceList' => $this->services ? $this->services()->pluck('name') : null,
            'products' => $this->products()->count(),
        ];
    }
}
