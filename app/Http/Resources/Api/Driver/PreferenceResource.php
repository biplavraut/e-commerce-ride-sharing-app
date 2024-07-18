<?php

namespace App\Http\Resources\Api\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class PreferenceResource extends JsonResource
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
            'smoking' => $this->smoking == 1,
            'childSeat' => $this->child_seat == 1,
            'handicapSupport' => $this->handicap_support == 1,
            'rental' => $this->rental == 1,
            'outstation' => $this->outstation == 1,
        ];
    }
}
