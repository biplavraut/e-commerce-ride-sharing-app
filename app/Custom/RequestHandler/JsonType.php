<?php

namespace App\Custom\RequestHandler;

class JsonType implements RequestHandlerInterface
{
    public function parse($column, $value, $model)
    {
        return is_array($value) ? json_encode($value) : json_encode([]);
    }
}
