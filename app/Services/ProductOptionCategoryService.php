<?php

namespace App\Services;

use App\ProductOptionCategory;

class ProductOptionCategoryService extends ModelService
{
    const MODEL = ProductOptionCategory::class;

    public function changeOrder(array $orderedCategoryIds)
    {
        foreach ($orderedCategoryIds as $key => $orderedCategoryId) {
            $this->update($orderedCategoryId, ['order' => $key + 1]);
        }
    }
}
