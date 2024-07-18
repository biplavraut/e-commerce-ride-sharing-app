<?php

namespace App\Services;

use App\InHouseRiderPaymentLog;

class InHouseRiderLogService extends ModelService
{
	const MODEL = InHouseRiderPaymentLog::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
