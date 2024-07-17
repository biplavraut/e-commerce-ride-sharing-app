<?php

namespace App\Http\Resources\Admin;

class DiscountResource extends CommonResource
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
            'discount_value' => $this->discount_value,
            'discount_type' => $this->discount_type,
            'applied_from' => $this->applied_from,
            'applied_till' => $this->applied_till,
            'status' => $this->status == 0?false:true,
            'created_at' => $this->created_at,
            'updated_at' => date('d F, Y - h:i A', strtotime($this->updated_at))
        ];
    }
}
