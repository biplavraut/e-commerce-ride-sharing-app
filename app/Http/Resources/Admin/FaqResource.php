<?php

namespace App\Http\Resources\Admin;

class FaqResource extends CommonResource
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
            'faq_title' => $this->faq_title,
            'faq_description' => $this->faq_description,
            'order' => $this->order,
            'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
        ];
    }
}
