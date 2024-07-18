<?php

namespace App\Services;

use App\Voucher;

class VoucherService extends ModelService
{
	const MODEL = Voucher::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
