<?php

namespace App\Http\Resources\Admin;


class SubscriptionPackageResource extends CommonResource
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
            'type' => $this->type,
            'two_wheel_value' => $this->two_wheel_value,
            'four_wheel_value' => $this->four_wheel_value,
            'duration' => $this->duration,
            'hide' => $this->hide == 1,
            'riderCount' => $this->drivers()->count(),
            'created_at' => $this->created_at,
        ];
    }
}
