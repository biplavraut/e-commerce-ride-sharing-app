<?php

namespace App\Custom\RequestHandler;

use Carbon\Carbon;

class DateType implements RequestHandlerInterface
{
	public function parse($column, $value, $model)
	{
		if (!$value) {
			return null;
		}

		if ($value instanceof Carbon) {
			return $value->toDateString();
		}

		return Carbon::parse($value)->toDateString();
	}
}
