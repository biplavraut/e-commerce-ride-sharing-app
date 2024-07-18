<?php

namespace App\Services;

use App\Launchpad;

class LaunchpadService extends ModelService
{
	const MODEL = Launchpad::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function changeOrder(array $ids)
	{
		foreach ($ids as $key => $id) {
			$this->update($id, ['order' => $key + 1]);
		}
	}
}
