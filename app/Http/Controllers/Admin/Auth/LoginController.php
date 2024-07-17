<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Services\AdminService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
	protected $redirectTo = '/admin';
	/**
	 * @var AdminService
	 */
	private $adminService;

	public function __construct(AdminService $adminService)
	{
		$this->middleware('guest:admin')->except('logout');
		$this->adminService = $adminService;
	}

	public function showLoginFrom()
	{
		return view('admin.auth.login');
	}

	protected function credentials(Request $request)
	{
		$credentials = $request->only($this->username(), 'password');

		$credentials['verified'] = 1;

		return $credentials;
	}

	protected function sendFailedLoginResponse(Request $request)
	{
		$admin = $this->adminService->findOrFailBy($this->username(), $request->email);

		if (!$admin->isVerified()) {
			$errorMessage['verified'] = [Lang::get('auth.not_verified')];
		}
		$errorMessage[ $this->username() ] = [trans('auth.failed')];

		throw ValidationException::withMessages($errorMessage);
	}

	protected function loggedOut(Request $request)
	{
		return redirect()->route('admin.login.form');
	}

	protected function guard()
	{
		return auth()->guard('admin');
	}
}
