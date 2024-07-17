<?php

namespace App\Services;

use App\Unit;

class UnitService extends ModelService
{
	const MODEL = Unit::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
