<?php

namespace App\Services;

use App\Services\ModelService;
use App\Tag;
use Illuminate\Support\Str;

class TagService extends ModelService
{
    const MODEL = Tag::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function saveMany($tags)
    {
        $tagId = [];

        foreach ($tags as $tag) {
            $tagId[] = Tag::firstOrCreate(['slug' => Str::slug($tag)], ['name' => $tag])->id;
        }

        return $tagId;
    }
}
