<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserNotifcationResource extends JsonResource
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
            'task' => $this->task ?? '',
            'title' => $this->title,
            'message' => $this->message,
            'isGlobal' => false,
            'type' => $this->type ?? '',
            'createdAt' => $this->created_at->diffForHumans()
        ];
    }
}
