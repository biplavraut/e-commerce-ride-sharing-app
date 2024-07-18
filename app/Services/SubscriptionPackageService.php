<?php

namespace App\Services;

use App\SubscriptionPackage;

class SubscriptionPackageService extends ModelService
{
	const MODEL = SubscriptionPackage::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
