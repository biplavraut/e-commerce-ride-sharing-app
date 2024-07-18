<?php

namespace App\Services;

use App\PaymentLog;

class PaymentLogService extends ModelService
{
	const MODEL = PaymentLog::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
