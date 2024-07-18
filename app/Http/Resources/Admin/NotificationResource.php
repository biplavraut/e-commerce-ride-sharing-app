<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;

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
        $data = json_decode($this->data);
        return [
            'id'         => $this->id,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'read_at'    => $this->read_at,
            'message'    => $data->message ?? 'This is test message.',
            'type'       => kebab_case(class_basename($this->type)),
            'data'       => $data,
            'from'       => [
                'name'  => $data->first_name ?? '',
                'image' => $data->image->image ?? '',
            ],
        ];
    }
}
