<?php

namespace App\Services;

use App\Prescription;

class PrescriptionService extends ModelService
{
    const MODEL = Prescription::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }
}
