<?php

namespace App\Http\Resources\Admin;

class SocialResource extends CommonResource
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
			'id'      => $this->id,
			'name'    => $this->name,
			'url'     => $this->url,
			'icon'    => $this->imageUrl('icon'),
			'icon50'  => $this->cropImage(50, 50, 'icon'),
			'icon150' => $this->cropImage(150, 150, 'icon'),
			'order'   => $this->order,
		];
	}
}
