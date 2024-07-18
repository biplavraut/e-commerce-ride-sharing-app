<?php

namespace App\Services;

use App\Partner;

class PartnerService extends ModelService
{
	const MODEL = Partner::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function changeOrder(array $orderIds)
	{
		foreach ($orderIds as $key => $id) {
			$this->update($id, ['order' => $key + 1]);
		}
	}
}
