<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Str;
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
            'licenseExpiryDate' => $this->license_expiry,
            'licenseNo' => $this->license_no,
            'license' => Str::contains($this->license, 'no-image') ? "" : $this->license,
            'blueBook' => Str::contains($this->blue_book, 'no-image') ? "" : $this->blue_book,
            'blueBookSec' => Str::contains($this->blue_book_sec, 'no-image') ? "" : $this->blue_book_sec,
            'blueBookTrd' => Str::contains($this->blue_book_trd, 'no-image') ? "" : $this->blue_book_trd,
            'blueBookExpiryDate' => $this->bluebook_expiry,
            'picture' => Str::contains($this->picture, 'no-image') ? "" : $this->picture,
            'image' => Str::contains($this->driver->image, 'no-image') ? "" : $this->driver->image,
            'ownerName' => $this->owner_name,
            'ownerContact' => $this->owner_contact,
            'status' => $this->status == 1,
            'dpcumentState' =>  $this->driver->documentState() == 0,
            'verified' => $this->driver->isVerified()
        ];
    }
}
