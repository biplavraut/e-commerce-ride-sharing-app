<?php

namespace App\Http\Resources\Api\Driver;

use App\Municipality;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $list = Municipality::where('district_name', $this->district_name)->orderBy('name')->get();
        return [
            'district' => $this->district_name,
            'municipalities' => MunicipalityResource::collection($list),
            'total' => $list->count()
        ];
    }
}
