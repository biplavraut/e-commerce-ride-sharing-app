<?php

namespace App\Services;

use App\DriverPaymentSettlement;

class DriverPaymentSettlementService extends ModelService
{
	const MODEL = DriverPaymentSettlement::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->orderBy('payable_amount', 'DESC')->paginate($limit, $columns);
	}
}