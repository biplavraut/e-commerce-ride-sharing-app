<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryLessResource extends JsonResource
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
            'id'        => $this['id'],
            'category_id' => $this['category_id'],
            'name'      => $this['name'],
            'slug'      => $this['slug'],
            'batch'      => $this['batch'],
            'parent'    => $this['parent'],
            'parent_id' => $this['parent_id'],
            'image'     => $this['image'],
        ];
    }
}
