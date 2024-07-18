<?php

namespace App\Services;

use App\LaunchpadCategory;

class LaunchpadCategoryService extends ModelService
{
    const MODEL = LaunchpadCategory::class;

    public function getForIndex($limit)
    {
        return $this->query()->orderBy('order')->paginate($limit);
    }

    public function changeOrder(array $orderedCategoryIds)
    {
        foreach ($orderedCategoryIds as $key => $orderedCategoryId) {
            $this->update($orderedCategoryId, ['order' => $key + 1]);
        }
    }

    public function getCategories()
    {
        return $this->query()->orderBy('order')->get();
    }
}
