<?php

namespace App\Services;

use App\AcademyContent;

class AcademyContentService extends ModelService
{
	const MODEL = AcademyContent::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
