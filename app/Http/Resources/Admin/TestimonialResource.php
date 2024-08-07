<?php

namespace App\Http\Resources\Admin;

class TestimonialResource extends CommonResource
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
			'id'       => $this->id,
			'name'     => $this->name,
			'image'    => $this->imageUrl(),
			'image50'  => $this->cropImage(50, 50),
			'image150' => $this->cropImage(150, 150),
			'message'  => $this->message,
		];
	}
}
