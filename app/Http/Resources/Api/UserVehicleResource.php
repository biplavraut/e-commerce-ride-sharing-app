<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVehicleResource extends JsonResource
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
            'id'  =>  $this->id,
            'user_id' =>  $this->user != null ? $this->user : null,
            'vehicle_color' =>  $this->vehicle_color,
            'main_type' => $this->main_type,
            'type' => $this->type,
            'reg_no'  => $this->reg_no,
            'fuel_sharing_km' => $this->fuel_sharing_km,
            'license_image' => $this->license_image,
            'vehicle_image' => $this->vehicle_image,
            'offering_seats' => $this->offering_seats,
            'check_point' => $this->check_point,
            'features' => $this->features,
            'remarks' => $this->remarks,
            'is_default' => $this->is_default,
            'is_verified' => $this->is_verified,
        ];
    }
}
