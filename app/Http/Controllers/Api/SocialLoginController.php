<?php

namespace App\Http\Controllers\Api;

use App\Custom\Abstracts\SocialLogin;
use App\Custom\FacebookLogin;
use App\Custom\GoogleLogin;
use App\Http\Requests\Api\SocialLoginRequest;
use App\Http\Resources\UserResource;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class SocialLoginController extends CommonController {
	private $loginPortals = [
		'facebook' => FacebookLogin::class,
		'google'   => GoogleLogin::class,
	];

	/**
	 * @param SocialLoginRequest $request
	 *
	 * @return UserResource
	 * @throws \Exception
	 */
	public function login(SocialLoginRequest $request) {
		$loginPortalType = $request->input('from');

		$loginPortalClass = $this->getLoginPortalClass($loginPortalType);

		$socialLogin = $this->verifyLogin(new $loginPortalClass);

		$email = $request->input('email');
		if($socialLogin->isValid()) {
			$user              = User::where('email', $email)->first() ?? new User;
			$user->name        = $request->input('name');
			$user->email       = $email;
			$user->social_id   = $request->input('socialId');
			$user->social_from = $loginPortalType;
			$user->password    = bcrypt(str_random(10));
			$user->verified    = 1;
			$user->save();

			$user->access_token = auth()->guard('api')->login($user);
			$user->save();

			return new UserResource($user);
		}

		throw new \Exception($socialLogin->getErrorMessages(), Response::HTTP_UNPROCESSABLE_ENTITY);
	}

	/**
	 * Check if login is valid
	 *
	 * @param SocialLogin $loginPortal
	 *
	 * @return SocialLogin
	 */
	private function verifyLogin(SocialLogin $loginPortal): SocialLogin {
		return $loginPortal->verify();
	}

	/**
	 * Generate class from login portal type
	 *
	 * @param string $loginPortal
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function getLoginPortalClass(string $loginPortal): string {
		$class = $this->loginPortals[ $loginPortal ];

		if(!class_exists($class)) {
			throw new \Exception("{$class} class does not exist.");
		}

		return $class;
	}
}
