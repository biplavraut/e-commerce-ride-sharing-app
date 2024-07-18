<?php

namespace App\Http\Resources\Admin;

class VoucherResource extends CommonResource
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
            'user' => $this->user()->select(['id', 'first_name', 'last_name', 'phone'])->first(),
            'amount' => $this->amount,
            'code' => $this->code,
            'used' => $this->used,
            'created_at' => $this->created_at,
        ];
    }
}
