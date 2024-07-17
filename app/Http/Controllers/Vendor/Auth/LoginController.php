<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Services\VendorService;
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
    protected $redirectTo = '/vendor';
    /**
     * @var VendorService
     */
    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->middleware('guest:vendor')->except('logout');
        $this->vendorService = $vendorService;
    }

    public function showLoginFrom()
    {
        return view('vendor.auth.login');
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        $credentials['verified'] = 1;

        return $credentials;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $vendor = $this->vendorService->findOrFailBy($this->username(), $request->email);

        if (!$vendor->isVerified()) {
            $errorMessage['verified'] = [Lang::get('auth.not_verified')];
        }
        $errorMessage[ $this->username() ] = [trans('auth.failed')];

        throw ValidationException::withMessages($errorMessage);
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('vendor.login.form');
    }

    protected function guard()
    {
        return auth()->guard('vendor');
    }
}
