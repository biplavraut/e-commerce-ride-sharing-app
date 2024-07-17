<?php

namespace App\Http\Resources\Admin;

class ItemsResource extends CommonResource
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
            'name' => $this->name,
            'flat_price' => $this->flat_price,
            'added_per_km_price' => $this->added_per_km_price,
            'added_weightprice_per_kg' => $this->added_weightprice_per_kg,
            'status' => $this->status,
            'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
