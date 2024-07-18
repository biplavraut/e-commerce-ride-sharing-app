<?php

namespace App\Custom\RequestHandler;

class PasswordType implements RequestHandlerInterface
{
	public function parse($column, $value, $model)
	{
		if ($value) {
			return bcrypt($value);
		}

		if ($model->exists) {
			return $model->getOriginal($column);
		}

		return null;
	}
}
