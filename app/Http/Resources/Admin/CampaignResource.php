<?php

namespace App\Http\Resources\Admin;

class CampaignResource extends CommonResource
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
			'held_on' => $this->held_on,
			'winners' => $this->winners,
			'prizes' => $this->prizes,
			'types' => $this->types,
			'description' => $this->description,
			'user_type' => $this->user_type,
			'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
		];
	}
}
