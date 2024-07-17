<?php

namespace App\Http\Resources\Api\Launchpad;

use Illuminate\Http\Resources\Json\JsonResource;

class LaunchpadCategoryResource extends JsonResource
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
            'id'      => $this->id,
            'name'    => $this->name,
            'description'   => $this->description ?? '',
            'launchpads' => LaunchpadResource::collection($this->launchpads()->where('hide', 0)->orderBy('order')->get())
        ];
    }
}
