<?php

namespace App\Services;

use App\VendorReviewRating;

class VendorReviewRatingService extends ModelService
{
	const MODEL = VendorReviewRating::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->where('verified', 0)->latest()->paginate($limit, $columns);
	}
}
