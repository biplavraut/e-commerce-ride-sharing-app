<?php

namespace App\Http\Resources\Admin;

class GogoAdResource extends CommonResource
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
			'title'       => $this->title,
			'url'         => $this->url,
			'image'       => $this->imageUrl(),
			'type'		  => $this->type,
			'serviceId'   => $this->service_id,
			'service'     => $this->service_id == 0 ? 'All' : $this->serviceName(),
			'hide'        => $this->hide == 1,
		];
	}
}
