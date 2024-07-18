<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class LayoutManagerResource extends CommonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'service'        => $this->service()->select(['id', 'name'])->first(),
            'order'        => $this->order,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'model_id_count' => count($this->model_id),
            'created_at' => $this->created_at,
        ];
    }
}
