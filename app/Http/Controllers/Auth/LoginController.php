<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';
	/**
	 * @var UserService
	 */
	private $userService;

	/**
	 * Create a new controller instance.
	 *
	 * @param UserService $userService
	 */
	public function __construct(UserService $userService)
	{
		$this->middleware('guest')->except('logout');
		$this->userService = $userService;
	}

	public function credentials(Request $request)
	{
		$credentials = $request->only($this->username(), 'password');

		$credentials['verified'] = 1;

		return $credentials;
	}

	protected function sendFailedLoginResponse(Request $request)
	{
		$user = $this->userService->findOrFailBy('email', $request->email);

		if (!$user->isVerified()) {
			$errorMessage['verified'] = [Lang::get('auth.not_verified')];
		}
		$errorMessage[ $this->username() ] = [trans('auth.failed')];

		throw ValidationException::withMessages($errorMessage);
	}

	protected function loggedOut(Request $request)
	{
		return redirect()->route('home');
	}

	 /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
		$website['company'] = Company::findOrFail(1);
        return view('auth.login', $website);
    }
}
