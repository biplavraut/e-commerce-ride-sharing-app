<?php

namespace App\Http\Resources\Admin;

class DonationResource extends CommonResource
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
            'user' => $this->user()->select(['id', 'first_name', 'last_name', 'country_code', 'phone'])->first(),
            'trust' => $this->trust,
            'donation' => $this->donation,
            'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
