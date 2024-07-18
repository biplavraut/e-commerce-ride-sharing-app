<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RidingPriceSurgeResource extends JsonResource
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
            'title' => $this->title,
            'from' => date('Y-m-d\TH:i', strtotime($this->from)),
            'to' => date('Y-m-d\TH:i', strtotime($this->to)),
            'price' => $this->price,
        ];
    }
}
