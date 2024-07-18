<?php

namespace App\Services;

use App\Slider;

class SliderService extends ModelService
{
	const MODEL = Slider::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}
}
