<?php

namespace App\Http\Resources\Admin;

class UtilityVoucherResource extends CommonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
