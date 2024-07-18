<?php

namespace App\Custom\RequestHandler;

use Carbon\Carbon;

class TimeType implements RequestHandlerInterface
{
	public function parse($column, $value, $model)
	{
		if (!$value) {
			return null;
		}

		if ($value instanceof Carbon) {
			return $value->toTimeString();
		}

		return Carbon::parse($value)->toTimeString();
	}
}
