<?php

namespace App\Services;

use App\RentalPackage;

class RentalPackageService extends ModelService
{
    const MODEL = RentalPackage::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function getPackages($name)
    {
        if (!$name) {
            return collect([]);
        }

        return $this->query()->where('name', 'LIKE', '%'. $name . '%')
                            ->orWhere('duration', 'LIKE', '%'. $name . '%')
                            ->orWhere('distance', 'LIKE', '%'. $name . '%')
                            ->orWhere('vehicles', 'LIKE', '%'. $name . '%')
                            ->get();
    }
}
