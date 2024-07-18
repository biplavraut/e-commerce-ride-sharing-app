<?php

namespace App\Services;

use App\RideOfferConf;

class RideOfferConfService extends ModelService
{
	const MODEL = RideOfferConf::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function latest()
	{
		return $this->query()->first();
	}
}
