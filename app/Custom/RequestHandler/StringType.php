<?php

namespace App\Custom\RequestHandler;

class StringType implements RequestHandlerInterface
{
	public function parse($column, $value, $model)
	{
		return $value;
	}
}