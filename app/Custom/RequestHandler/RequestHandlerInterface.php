<?php

namespace App\Custom\RequestHandler;

interface RequestHandlerInterface
{
	public function parse($column, $value, $model);
}
