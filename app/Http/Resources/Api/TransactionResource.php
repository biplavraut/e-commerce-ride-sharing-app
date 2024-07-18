<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'transactionId' => $this->transId(),
            'from' => $this->from,
            'paymentMode' => $this->payment_mode,
            'point' => number_format((float)$this->point, 2, '.', ''),
            'type' => $this->type,
            'message' => $this->message ?? '',
            'createdAt' => $this->created_at->diffForHumans(),
            'date' => $this->created_at,

        ];
    }
}
