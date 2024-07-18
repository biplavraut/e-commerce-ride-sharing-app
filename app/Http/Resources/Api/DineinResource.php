<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DineinResource extends JsonResource
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
            'vendor' => $this->vendor()->select(['id', 'business_name as businessName', 'address', 'city', 'phone'])->first(),
            'user' => $this->user()->select(['id', 'first_name as firstName', 'last_name as lastName', 'phone', 'image'])->first(),
            'date' => $this->date,
            'time' => $this->time,
            'peopleAttend' => $this->people_attend,
            'status' => $this->status,
            'totalBill' => $this->total_price ?? '',
            'bill' => $this->imageUrl('bill') ?? '',
            'status' => $this->status ?? '',
            'specialInstruction' => $this->special_instruction ?? '',
            'createdAt' => $this->created_at->diffForHumans(),
        ];
    }
}
