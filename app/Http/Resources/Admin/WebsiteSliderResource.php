<?php

namespace App\Http\Resources\Admin;

class WebsiteSliderResource extends CommonResource
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
            'slider_text' => $this->slider_text,
            'image' => $this->image,
            'hide' => $this->hide ==1 ,
            'created_at' => $this->created_at,
        ];
    }
}
