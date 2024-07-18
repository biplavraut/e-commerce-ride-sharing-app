<?php

namespace App\Http\Resources\Admin;


class PartnerResource extends CommonResource
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
            'vendor' => $this->vendor()->select(['id', 'business_name'])->first(),
            'hide' => $this->hide == 1,
            'order' => $this->order,
            'image' => $this->image,
            'expire_in' => $this->expire_in ? date('Y-m-d\TH:i', strtotime($this->expire_in)) : null,
            'created_at' => $this->created_at,
            'has_branches' => $this->has_branches,
            'parent_id' => $this->parent_id
        ];
    }
}
