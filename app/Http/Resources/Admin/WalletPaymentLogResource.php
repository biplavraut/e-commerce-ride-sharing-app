<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletPaymentLogResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'paymentMode' => $this->payment_mode,
            'token' => $this->token,
            'billAmount' => $this->bill_amt,
            'verified' => $this->verified,
            'ip' => $this->ip,
            'agent' => $this->agent,
            'action' => $this->action,
            'type' => $this->type,
            'response' => $this->response,
            'createdAt' => $this->created_at
        ];
    }
}
