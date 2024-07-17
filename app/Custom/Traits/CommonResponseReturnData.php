<?php

namespace App\Custom\Traits;

trait CommonResponseReturnData
{
	public function with($request)
	{
		return [
			'status' => true,
			'code'   => 200,
		];
	}
}
