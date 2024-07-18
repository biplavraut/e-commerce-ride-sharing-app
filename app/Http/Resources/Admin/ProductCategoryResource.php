<?php

namespace App\Http\Resources\Admin;

class ProductCategoryResource extends CommonResource
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
            'id'        => $this->id,
            'category_id' => $this->category_id,
            'isParent' => $this->isParentOfAll(),
            'name'      => $this->name,
            'slug'      => $this->slug,
            'batch'      => $this->batch ?? '',
            'parent'    => $this->parent,
            'parent_id' => $this->parent_id,
            'image'     => $this->image,
            'childCount' => $this->children()->count(),
            'image50'   => $this->cropImage(50, 50),
            'children' => ProductCategoryResource::collection($this->children)
        ];
    }

    /**
     * Dont convert the null value of the array with these keys to empty string
     *
     * @return array
     */
    protected function ignoreNullValueOfKeys(): array
    {
        return ['parent_id'];
    }
}
