<?php

namespace App\Http\Resources\Admin;

class AcademyContentResource extends CommonResource
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
            'image' => $this->image,
            'url' => $this->url,
            'fors' => $this->fors ,
            'video_url' => $this->video_url ,
            'description' => $this->description ,
            'created_at' => $this->created_at,
        ];
    }
}
