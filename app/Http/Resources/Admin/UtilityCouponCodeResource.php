<?php

namespace App\Http\Resources\Admin;

class UtilityCouponCodeResource extends CommonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArrayWithoutNullValues($request)
    {
        return [
			'id' => $this->id,
			'code' => $this->code,
			'amount' => $this->amount,
			'valid_till' => $this->valid_till,
            'users' => $this->users,
			'usedTimes' => $this->histories()->count(),
			'created_at' => date('d F, Y - h:i A', strtotime($this->created_at))
		];
    }
}
