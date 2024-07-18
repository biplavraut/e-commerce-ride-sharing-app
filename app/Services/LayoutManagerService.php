<?php

namespace App\Services;

use App\LayoutManager;
use Illuminate\Support\Facades\Redis;

class LayoutManagerService extends ModelService
{
    const MODEL = LayoutManager::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->orderBy('name')->paginate($limit, $columns);
    }

    public function changeOrder(array $ids)
    {
        foreach ($ids as $key => $id) {
            $this->update($id, ['order' => $key + 1]);

            try {
                $data = $this->query()->findOrFail($id);
                Redis::set('layoutUpdate_' . $data->service_id, json_encode($data));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
