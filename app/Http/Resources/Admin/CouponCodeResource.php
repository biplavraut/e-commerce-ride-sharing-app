<?php

namespace App\Http\Resources\Admin;

class CouponCodeResource extends CommonResource
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
			'code' => $this->code,
			'amount' => $this->amount,
			'valid_till' => $this->valid_till,
			'usedTimes' => $this->histories()->count(),
			'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
		];
	}
}
