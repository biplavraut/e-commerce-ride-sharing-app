<?php

namespace App\Services;

use App\DefaultConf;

class DefaultConfService extends ModelService
{
	const MODEL = DefaultConf::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function latest()
	{
		return $this->query()->first();
	}
}
