<?php

namespace App\Services;

use App\ProductOptionCategory;
use Illuminate\Support\Facades\Redis;

class ProductOptionCategoryService extends ModelService
{
    const MODEL = ProductOptionCategory::class;

    public function changeOrder(array $orderedCategoryIds)
    {
        foreach ($orderedCategoryIds as $key => $orderedCategoryId) {
            $this->update($orderedCategoryId, ['order' => $key + 1]);

            try {
                $data = $this->query()->findOrFail($orderedCategoryId);
                Redis::set('layoutUpdate_' . $data->service_id, json_encode($data));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
