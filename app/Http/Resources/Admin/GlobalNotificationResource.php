<?php

namespace App\Http\Resources\Admin;

class GlobalNotificationResource extends CommonResource
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
            'message' => $this->message,
            'to' => $this->for,
            'geo' => $this->geo == 1,
            'lat' => $this->lat,
            'long' => $this->long,
            'radius' => $this->radius,
            'created_at' => $this->created_at,
        ];
    }
}
