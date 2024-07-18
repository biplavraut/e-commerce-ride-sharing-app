<?php

namespace App\Services;

use App\OrderFeedback;

class OrderFeedbackService extends ModelService
{
	const MODEL = OrderFeedback::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
