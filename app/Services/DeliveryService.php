<?php

namespace App\Services;

use App\Delivery;

class DeliveryService extends ModelService
{
	const MODEL = Delivery::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->where('status', '!=', 'delivered')->Where('status', '!=', 'pending')->latest()->paginate($limit, $columns);
	}

	public function getCompletedCount()
	{
		return $this->query()->where('status', 'delivered')->count();
	}

	public function getOnGoingCount()
	{
		return $this->query()->where('status', '!=', 'delivered')->count();
	}
}
