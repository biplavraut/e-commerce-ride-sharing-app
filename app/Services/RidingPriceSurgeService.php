<?php

namespace App\Services;

use App\RidingFare;
use App\RidingPriceSurge;

class RidingPriceSurgeService extends ModelService
{
    const MODEL = RidingPriceSurge::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function saveMany($fare, $surges)
    {
        $surge = [];

        foreach ($surges as $surge) {
            $surge[] = RidingPriceSurge::create(['riding_fare_id' => $fare->id, 'title' => $surge['title'], 'from' => $surge['from'], 'to' => $surge['to'], 'price' => $surge['price']]);
        }

        return $surge;
    }

    public function updateMany($fare, $surges)
    {
        $newSurges = [];

        foreach ($surges as $key => $surge) {
            if ($surge['id'] == 0) {
                $newSurges[] = $surge;
            } else {
                $data['title'] = $surge['title'];
                $data['from'] = $surge['from'];
                $data['to'] = $surge['to'];
                $data['price'] = $surge['price'];
                $this->update($surge['id'], $data);
            }
        }

        if (count($newSurges) > 0) {
            $this->saveMany($fare, $newSurges);
        }
    }
}
