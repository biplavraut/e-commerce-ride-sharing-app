<?php

namespace App\Http\Resources\Admin;

class CategoryResource extends CommonResource
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
			'name'      => $this->name,
			'order'      => $this->order,
			'subtitle'      => $this->subtitle ?? '',
			'slug'      => $this->slug,
			'parent'    => $this->parent,
			'parent_id' => $this->parent_id,
			'image'     => $this->image,
			'enabled' => $this->enabled,
			'ondemand' => $this->ondemand,
			'opening_closing_time' => $this->opening_closing_time,
			'show_product_category' => $this->show_product_category,
			'count' => $this->registeredVendor()->count(),
			'category_count' => $this->productCategories()->count(),
			'slider_count' => $this->sliders()->count(),
			'image50'   => $this->cropImage(50, 50),
			'productOptionCount' => $this->productOptionCategories()->count(),
			'vendorOptionCount' => $this->vendorOptionCategories()->count(),
			'layoutCount' => $this->layoutManager()->count(),
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
