<?php

namespace App\Http\Resources\Admin;

class LaunchpadCategoryResource extends CommonResource
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
            'id'      => $this->id,
            'name'    => $this->name,
            'description'   => $this->description,
            'order'   => $this->order,
            'count' => $this->launchpads()->count()
        ];
    }
}
