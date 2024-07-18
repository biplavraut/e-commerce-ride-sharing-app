<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class AcademyContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'url' => $this->url ?? '',
            'videoUrl' => $this->video_url ?? '',
            'description' => $this->description ?? '' ,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
