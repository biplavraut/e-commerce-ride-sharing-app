<?php

namespace App\Services;

use App\SendItems;

class ItemsService extends ModelService
{
	const MODEL = SendItems::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
