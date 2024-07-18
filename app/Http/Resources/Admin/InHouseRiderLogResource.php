<?php

namespace App\Http\Resources\Admin;


class InHouseRiderLogResource  extends CommonResource
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
            'driver' => $this->driver()->select(['id', 'first_name', 'last_name', 'phone'])->first(),
            'order' => $this->order()->select(['id', 'ref_number', 'shipping_fee', 'total'])->first(),
            'receiver' => $this->receiver()->select(['id', 'name', 'email', 'type'])->first(),
            'total' => $this->total,
            'log' => $this->log,
            'created_at' => date('d F, Y - h:i A', strtotime($this->created_at)),
            'updated_at' => date('d F, Y - h:i A', strtotime($this->updated_at)),
        ];
    }
}
