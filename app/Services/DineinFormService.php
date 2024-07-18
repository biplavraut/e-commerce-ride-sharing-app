<?php

namespace App\Services;

use App\DineInForm;

class DineinFormService extends ModelService
{
	const MODEL = DineInForm::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
