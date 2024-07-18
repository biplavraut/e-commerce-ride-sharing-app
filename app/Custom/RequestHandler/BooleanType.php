<?php

namespace App\Custom\RequestHandler;

class BooleanType implements RequestHandlerInterface
{
	public function parse($column, $value, $model)
	{
		return $value === 1 || $value === 'true' || $value === true || $value === '1';
	}
}
