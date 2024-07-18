<?php

namespace App\Custom\RequestHandler;

use Carbon\Carbon;

class DatetimeType implements RequestHandlerInterface
{
	public function parse($column, $value, $model)
	{
		if (!$value) {
			return null;
		}

		if ($value instanceof Carbon) {
			return $value->toDateTimeString();
		}

		return Carbon::parse($value)->toDateTimeString();
	}
}