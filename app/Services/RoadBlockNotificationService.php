<?php

namespace App\Services;

use App\RoadBlockMessage;

class RoadBlockNotificationService extends ModelService
{
    const MODEL = RoadBlockMessage::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }
}
