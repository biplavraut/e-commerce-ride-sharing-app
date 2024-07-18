<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderFeedbackResource extends JsonResource
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
            'user' => $this->user()->select(['first_name', "last_name", 'phone'])->first(),
            'order' => $this->order()->select(['id', "ref_number", 'phone'])->first(),
            'feedback' => $this->feedback,
            'respond' => $this->respond,
            'admin' => new AdminResource($this->admin),
            'createdAt' => date('M d, h:i a', strtotime($this->created_at)),
            'updatedAt' => date('M d, h:i a', strtotime($this->updated_at))
        ];
    }
}
