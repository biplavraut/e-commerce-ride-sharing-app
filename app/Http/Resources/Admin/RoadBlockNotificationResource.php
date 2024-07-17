<?php

namespace App\Http\Resources\Admin;

class RoadBlockNotificationResource extends CommonResource
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
            'title' => $this->title,
            'image' => $this->imageUrl(),
            'description' => $this->description,
            'show_image_on_top' => $this->show_image_on_top,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
