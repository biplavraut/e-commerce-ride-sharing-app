<?php

namespace App\Services;

use App\Trip;

class TripService extends ModelService
{
	const MODEL = Trip::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->where('status', '=', 'started')->orWhere('status', '=', 'ongoing')->orWhere('status', '=', 'arrived')->latest()->paginate($limit, $columns);
	}

	public function getCompletedCount()
	{
		return $this->query()->where('status', 'completed')->count();
	}

	public function getOnGoingCount()
	{
		return $this->query()->whereIn('status', ['pending', 'paused', 'ongoing', 'arrived', 'started'])->count();
	}

	public function getStatusCount($status)
	{
		if ($status == "dispute") {
			return $this->query()
				->where('done', 0)
				->Where(function ($query) {
					$query->where('status', 'dispute');
					$query->orWhere('dispute', '!=', null);
				})->count();
		}

		if ($status == "accident") {
			return $this->query()
				->where('status', 'accident')
				->Where('done', 0)->count();
		}

		return $this->query()->where('status', $status)->count();
	}
}
