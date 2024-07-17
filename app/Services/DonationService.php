<?php

namespace App\Services;

use App\CouponCode;
use App\MyDonation;

class DonationService extends ModelService
{
	const MODEL = MyDonation::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
