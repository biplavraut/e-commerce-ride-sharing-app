<?php

namespace App\Services;

use App\CouponCode;

class CouponCodeService extends ModelService
{
	const MODEL = CouponCode::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
