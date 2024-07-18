<?php

namespace App\Services;

use App\GogoAd;

class GogoAdService extends ModelService
{
	const MODEL = GogoAd::class;

	public function getPaginatedList($limit, $columns = ['*'])
	{
		return $this->query()->paginate($limit, $columns);
	}
}
