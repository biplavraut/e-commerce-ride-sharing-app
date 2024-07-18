<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SettlementReportResource extends JsonResource
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
            'id'    => $this->vendor_id,
            'vendorId' => $this->vendor->vendorId(),
            'businessName'  => $this->vendor->business_name,
            'fullName'  => $this->vendor->first_name.' '.$this->vendor->last_name,
            'email' => $this->vendor->email,
            'phone' => $this->vendor->phone,
            'amount' => $this->amount,
            'log' => $this->log,
            'settledOn' => $this->created_at,
            'settledBy' => $this->admins->name ?? ''
        ];
    }
}
