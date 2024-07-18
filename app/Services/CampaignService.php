<?php

namespace App\Services;

use App\Campaign;

class CampaignService extends ModelService
{
	const MODEL = Campaign::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
