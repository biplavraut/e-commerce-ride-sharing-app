<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdditionServiceResource extends CommonResource
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
			'image'     => $this->image,
			'enabled' => $this->enabled,
			'enabledPromo' => $this->enabled_promo,
			'image50'   => $this->cropImage(50, 50),
			'cashback'   => $this->cashback,
		];
    }
}
