<?php

namespace App\Services;

use App\UtilityCouponCode;

class UtilityCouponCodeService extends ModelService
{
	const MODEL = UtilityCouponCode::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
