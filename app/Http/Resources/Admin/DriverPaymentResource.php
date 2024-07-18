<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverPaymentResource extends JsonResource
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
            'driver' => $this->driver()->select(['id', 'first_name', 'last_name', 'phone', 'email', 'is_blocked'])->first(),
            'type' => $this->type,
            'bankName' => $this->bank_name,
            'accountName' => $this->account_name,
            'accountNo' => $this->account_no,
            'branch' => $this->branch,
            'walletProvider' => $this->wallet_provider,
            'walletNo' => $this->wallet_no,
            'payableAmount' => $this->payable_amount,
            'donationAmount' => $this->donation_amount,
            'receivableAmount' => $this->receivable_amount,
        ];
    }
}
