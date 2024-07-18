<?php

namespace App\Services;

use App\OrderOfferConf;

class OrderOfferConfService extends ModelService
{
	const MODEL = OrderOfferConf::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function latest()
	{
		return $this->query()->first();
	}
}
