<?php

namespace App\Services;

use App\DeliveryJunction;

class DeliveryJunctionService extends ModelService
{
    const MODEL = DeliveryJunction::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }
}
