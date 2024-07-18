<?php

namespace App\Services;

use App\UtilityVoucher;

class UtilityVoucherService extends ModelService
{
	const MODEL = UtilityVoucher::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
