<?php

namespace App\Exceptions\Api;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidationFailed extends Exception
{
	protected $customMessage;

	public function __construct(Validator $validator)
	{
		parent::__construct();
		$this->customMessage = $validator->errors()->toArray();
	}

	public function render($request)
	{
		return response()->json([
			'status'     => false,
			'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
			'message'    => $this->getCustomMessage(),
		], Response::HTTP_OK);
	}

	public function getCustomMessage()
	{
		return array_map(function ($message) {
			$key = array_keys($message)[0];

			return $key = $message[ $key ];
		}, $this->customMessage);
	}

}
