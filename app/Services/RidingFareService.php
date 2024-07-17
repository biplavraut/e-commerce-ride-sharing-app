<?php

namespace App\Services;

use App\RidingFare;

class RidingFareService extends ModelService
{
    const MODEL = RidingFare::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function getFares($name)
    {
        if (!$name) {
            return collect([]);
        }

        return $this->query()->where('vehicle', 'LIKE', $name . '%')
            ->get();
    }
}
