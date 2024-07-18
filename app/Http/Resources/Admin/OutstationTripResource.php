<?php

namespace App\Http\Resources\Admin;

class OutstationTripResource extends CommonResource
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
			'user' => $this->user,
			'driver' => new DriverResource($this->driver),
			'from' => $this->from,
			'to' => $this->to,
			'package' => $this->package,
			'vehicle' => $this->vehicle_type,
			'payment_mode' => $this->payment_mode,
			'status' => $this->status,
			'price' => $this->price,
			'starts_at' => $this->starts_at,
			'ends_at' => $this->ends_at,
			'completed_at' => $this->completed_at,
			'created_at' => $this->created_at,
		];
	}
}
