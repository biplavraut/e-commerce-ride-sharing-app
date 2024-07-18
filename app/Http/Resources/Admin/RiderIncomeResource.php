<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RiderIncomeResource extends JsonResource
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
            'id' => $this->driver->id,
            'totalRides' => $this->total,
            'collected' => $this->price,
            'image' => $this->driver->image,
            'name' => $this->driver->first_name . ' ' . $this->driver->last_name,
            'phone' => $this->driver->country_code . '' . $this->driver->phone,
        ];
    }
}
