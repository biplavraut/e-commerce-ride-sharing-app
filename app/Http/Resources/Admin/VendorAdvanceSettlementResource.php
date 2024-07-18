<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorAdvanceSettlementResource extends JsonResource
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
            'businessName' => $this->business_name,
            'fullName' => $this->first_name .' '. $this->last_name,
            'email' =>$this->email,
            'phone' =>$this->phone,
            'verified' => $this->verified,
            'lastAdvancePaid' => $this->advanceLogs()->latest()->first() ? date_format( $this->advanceLogs()->latest()->first()->created_at,'Y-m-d H:i:s') : 'No Advance: '.date_format($this->created_at,'Y-m-d H:i:s'),            
            'vendorAdvanceBalance' => $this->vendorAdvanceSettlementBalance()
        ];
    }
}
