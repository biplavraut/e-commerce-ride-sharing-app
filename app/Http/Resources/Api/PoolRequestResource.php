<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PoolRequestResource extends JsonResource
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
            'id'  =>  $this->id,
            'requester' => new UserResource($this->user),
            'userId' => $this->requested_user_id,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'seat' => $this->seat,
            'createdAt' => $this->created_at
        ];
    }
}
