<?php

namespace App\Http\Resources\Admin;

class PaymentLogResource extends CommonResource
{
    /**
     * Transform the resource into an array by changing null values to empty string.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArrayWithoutNullValues($request)
    {

        return [
            'id' => $this->id,
            'task_id' => $this->task_id,
            'user' => $this->user()->select(['id', 'first_name', 'last_name', 'phone'])->first(),
            'task' => $this->paymentTask(),
            'vendor' =>  $this->vendor(),
            'payment_mode' => $this->payment_mode,
            'token' => $this->token,
            'bill_amt' => $this->bill_amt,
            'verified' => $this->verified == 1,
            'ip' => $this->ip,
            'agent' => $this->agent,
            'action' => $this->action,
            'type' => $this->type,
            'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
