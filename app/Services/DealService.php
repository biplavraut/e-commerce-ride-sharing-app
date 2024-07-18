<?php

namespace App\Services;

use App\Deal;

class DealService extends ModelService
{
	const MODEL = Deal::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->with('category')->latest()->paginate($limit, $columns);
	}
}
