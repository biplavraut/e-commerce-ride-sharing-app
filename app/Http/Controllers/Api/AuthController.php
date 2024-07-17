<?php

namespace App\Http\Controllers\Api;

use App\Custom\HttpRequest;
use App\Exceptions\Api\CustomException;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\SignupRequest;
use App\Http\Resources\UserResource;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends CommonController {
	/**
	 * @param LoginRequest $request
	 *
	 * @return UserResource
	 * @throws CustomException
	 */
	public function login(LoginRequest $request) {
		$email = $request->input('email');

		$this->attemptLogin($request, $email);

		$user = $this->createAccessTokenIfAbsent($email);

		return new UserResource($user);
	}

	/**
	 * @param SignupRequest $request
	 *
	 * @return UserResource
	 * @throws CustomException
	 */
	public function signup(SignupRequest $request) {
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => bcrypt($request->password),
		]);

		// request for access and refresh token for api authorization
		$tokens = $this->requestAccessAndRefreshTokens($user);

		$user->access_token  = $tokens['access_token'];
		$user->refresh_token = $tokens['refresh_token'];
		$user->save();

		return new UserResource($user);
	}

	/**
	 * @param LoginRequest $request
	 * @param $email
	 *
	 * @throws CustomException
	 */
	private function attemptLogin(LoginRequest $request, $email) {
		if(!auth()->attempt(['email' => $email, 'password' => $request->input('password')])) {
			throw new CustomException('Login failed', Response::HTTP_UNAUTHORIZED);
		}
	}

	/**
	 * @param $email
	 *
	 * @return mixed
	 */
	private function createAccessTokenIfAbsent($email) {
		$user = User::where('email', $email)->first();
		if(is_null($user->access_token)) {
			$user->access_token = $user->createToken('Access Token', ['*'])->accessToken;
			$user->save();
		}

		return $user;
	}

	/**
	 * @param $user
	 *
	 * @return mixed
	 * @throws CustomException
	 */
	private function requestAccessAndRefreshTokens($user) {
		$tokens = (new HttpRequest)->post(url('oauth/token'), [
			'username'      => request()->email,
			'password'      => request()->password,
			'grant_type'    => 'password',
			'client_id'     => 2,
			'client_secret' => config('services.passport.client_2_secret'),
			'scope'         => '*',
		])->execute();

		// if there is an error, the $data variable will have error message associated with 'error' key.
		if(array_key_exists('error', $tokens)) {
			$user->delete();
			throw new CustomException($tokens['message'], Response::HTTP_BAD_REQUEST);
		}

		return $tokens;
	}
}
