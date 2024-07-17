<?php

namespace App\Custom\RequestHandler;

use Illuminate\Support\Str;

class SlugType implements RequestHandlerInterface
{
    public function parse($column, $value, $model)
    {
        if ($this->isFromUpdate($model)) {
            return $model->slug;
        }

        return Str::slug($value) . '-' . time();
    }

    /**
     * Check if data is from update
     *
     * @param $model
     *
     * @return bool
     */
    private function isFromUpdate($model): bool
    {
        return $model->exists;
    }
}