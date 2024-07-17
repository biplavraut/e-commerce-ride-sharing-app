<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOpeningTimeResource extends JsonResource
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
            'sunOpen' => $this->sun_opening,
            'sunClose' => $this->sun_closing,
            'monOpen' => $this->mon_opening,
            'monClose' => $this->mon_closing,
            'tueOpen' => $this->tue_opening,
            'tueClose' => $this->tue_closing,
            'wedOpen' => $this->wed_opening,
            'wedClose' => $this->wed_closing,
            'thuOpen' => $this->thu_opening,
            'thuClose' => $this->thu_closing,
            'friOpen' => $this->fri_opening,
            'friClose' => $this->fri_closing,
            'satOpen' => $this->sat_opening,
            'satClose' => $this->sat_closing,
        ];
    }
}
