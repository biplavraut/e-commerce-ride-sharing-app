<?php

namespace App\Services;

use App\Faq;

class FaqService extends ModelService
{
	const MODEL = Faq::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
