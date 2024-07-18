<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchVendorResource extends JsonResource
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
            'id'    => $this->id,
            'businessName'  => $this->business_name,
            'image' => $this->image,
            'image50'     => $this->cropImage(50, 50),
            'city' => $this->city,
            'address' => $this->address,
            'phone' => $this->phone,
            'isHidden' => $this->is_hidden == 1,
            'averageRating' => $this->averageRating() < 1 ? 5 : $this->averageRating(),
            'showOpeningClosingTime' => $this->showOpeningClosing(),
            'services' => $this->services()->count() > 0 ?  $this->services()->where('enabled', 1)->select(['category_id as id', 'name'])->get() : [],
            'takeaway' => $this->takeaway == 1,
            'dineIn' => $this->dine_in == 1,
        ];
    }
}
