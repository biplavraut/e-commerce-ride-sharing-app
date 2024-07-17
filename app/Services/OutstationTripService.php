<?php

namespace App\Services;

use App\OutstationTrip;

class OutstationTripService extends ModelService
{
	const MODEL = OutstationTrip::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function getTrip($name)
	{
		if (!$name) {
			return collect([]);
		}

		return $this->query()->where('vehicle_type', 'LIKE', $name . '%')
			->orWhere('status', 'LIKE', $name . '%')
			->orWhere('payment_mode', 'LIKE', $name . '%')
			->take(10)
			->get();
	}
}
