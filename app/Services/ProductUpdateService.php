<?php

namespace App\Services;

use App\ProductUpdate;

class ProductUpdateService extends ModelService
{
    const MODEL = ProductUpdate::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }
}
