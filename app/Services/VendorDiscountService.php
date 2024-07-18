<?php

namespace App\Services;

use App\VendorDiscount;

class VendorDiscountService extends ModelService
{
	const MODEL = VendorDiscount::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()
		->with(array('vendor' => function ($query) {
			$query->select('id', 'business_name');
		}))
		->latest()
		->paginate($limit, $columns);
	}
}
