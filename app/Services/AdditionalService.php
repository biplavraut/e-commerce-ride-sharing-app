<?php

namespace App\Services;

use App\AdditionalService as Addition;


class AdditionalService extends ModelService
{
	const MODEL = Addition::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
