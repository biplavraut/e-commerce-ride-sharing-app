<?php

namespace App\Http\Resources\Admin;

class LaunchpadResource extends CommonResource
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
            'category' => $this->category,
            'name' => $this->name,
            'url' => $this->url,
            'hide' => $this->hide == 1,
            'order' => $this->order,
            'image' => $this->image,
            'description' => $this->description,
            'created_at' => $this->created_at
        ];
    }
}
