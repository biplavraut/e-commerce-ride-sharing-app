<?php

namespace App\Http\Resources\Admin;

class AcademySliderResource extends CommonResource
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
            'name' => $this->name,
            'image' => $this->image,
            'url' => $this->url,
            'fors' => $this->fors ,
            'created_at' => $this->created_at,
        ];
    }
}
