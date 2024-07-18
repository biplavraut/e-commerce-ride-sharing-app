<?php

namespace App\Services;

use App\PrescriptionBill;

class PrescriptionBillService extends ModelService
{
	const MODEL = PrescriptionBill::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
