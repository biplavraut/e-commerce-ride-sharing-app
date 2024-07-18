<?php

namespace App\Services;

use App\AcademySlider;

class AcademySliderService extends ModelService
{
	const MODEL = AcademySlider::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
