<?php

namespace App\Services;

use App\PremiumPlace;

class PremiumPlaceService extends ModelService
{
    const MODEL = PremiumPlace::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function getPlaces($name)
    {
        if (!$name) {
            return collect([]);
        }

        return $this->query()->where('location', 'LIKE', '%' . $name . '%')
            ->orWhere('price', 'LIKE', $name . '%')
            ->orWhere('radius', 'LIKE', $name . '%')
            ->orWhere('lat', 'LIKE', $name . '%')
            ->orWhere('long', 'LIKE', $name . '%')
            ->take(10)
            ->get();
    }

    public function getPopularPlaces($name)
    {
        if (!$name) {
            return collect([]);
        }

        return $this->query()->where('location', 'LIKE', '%' . $name . '%')
            ->Where('popular', 1)
            ->orWhere('price', 'LIKE', $name . '%')
            ->orWhere('radius', 'LIKE', $name . '%')
            ->orWhere('lat', 'LIKE', $name . '%')
            ->orWhere('long', 'LIKE', $name . '%')
            ->take(10)
            ->get();
    }
}
