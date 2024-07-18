<?php

namespace App\Services;

use App\RentalTrip;

class RentalTripService extends ModelService
{
    const MODEL = RentalTrip::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function getTrip($name)
    {
        if (!$name) {
            return collect([]);
        }

        return $this->query()->where('vehicle_type', 'LIKE', $name . '%')
            ->orWhere('status', 'LIKE', $name . '%')
            ->orWhere('payment_mode', 'LIKE', $name . '%')
            ->get();
    }
}
