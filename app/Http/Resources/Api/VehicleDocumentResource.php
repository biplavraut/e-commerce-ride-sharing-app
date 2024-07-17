<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleDocumentResource extends JsonResource
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
            'regNumber' => $this->reg_no,
            'type' => $this->type,
            'manufacturingYear' => $this->manufacturing_year,
            'licenseCategory' => $this->license_category,
            'color' => $this->color ?? '',
            'plateNo' => $this->plate_no ?? '',
            'licenseNo' => $this->license_no,
            'license' => $this->license,
            'blueBook' => $this->blue_book,
            'picture' => $this->picture,
            'image' => $this->driver->image,
            'verified' => $this->driver->isVerified()
        ];
    }
}
