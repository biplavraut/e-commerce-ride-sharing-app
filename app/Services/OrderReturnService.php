<?php

namespace App\Services;

use App\OrderReturn;

class OrderReturnService extends ModelService
{
	const MODEL = OrderReturn::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
