<?php

namespace App\Http\Resources\Admin;

class NotificationResource extends CommonResource
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
            'id'         => $this->id,
            'created_at' => $this->created_at->diffForHumans(),
            'read_at'    => optional($this->read_at)->toDateTimeString(),
            'message'    => $this->data['message'] ?? 'This is test message.',
            'type'       => kebab_case(class_basename($this->type)),
            'data'       => $this->data,
            'from'       => [
                'name'  => $this->data['first_name'],
                'image' => $this->data['image'],
            ],
        ];
    }
}
