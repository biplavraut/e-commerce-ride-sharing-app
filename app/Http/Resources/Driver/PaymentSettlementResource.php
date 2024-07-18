<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentSettlementResource extends JsonResource
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
            'type' => $this->type,
            'bankName' => $this->bank_name ?? '',
            'accountName' => $this->account_name ?? '',
            'accountNo' => $this->account_no ?? '',
            'branch' => $this->branch ?? '',
            'walletProvider' => $this->wallet_provider ?? '',
            'walletNo' => $this->wallet_no ?? ''
        ];
    }
}
