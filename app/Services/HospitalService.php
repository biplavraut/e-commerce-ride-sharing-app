<?php

namespace App\Services;

use App\Hospital;

class HospitalService extends ModelService
{
    const MODEL = Hospital::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }
}
