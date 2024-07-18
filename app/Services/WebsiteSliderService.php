<?php

namespace App\Services;

use App\WebsiteSlider;

class WebsiteSliderService extends ModelService
{
	const MODEL = WebsiteSlider::class;

	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->latest()->paginate($limit, $columns);
	}

	public function changeOrder(array $options)
    {
        foreach ($options as $key => $optionId) {
            $this->update($optionId, ['order' => $key + 1]);
        }
    }
}
