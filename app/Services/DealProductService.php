<?php

namespace App\Services;

use App\DealProduct;

class DealProductService extends ModelService
{
	const MODEL = DealProduct::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->with('category')->latest()->paginate($limit, $columns);
	}

	public function changeOrder(array $ids)
    {
        foreach ($ids as $key => $id) {
            $this->update($id, ['order' => $key + 1]);
        }
    }
}
