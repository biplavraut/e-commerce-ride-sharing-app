<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Role;
use App\Vendor;
use Illuminate\Http\Request;
use App\Mail\VendorVerifyEmail;
use App\Services\VendorService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use App\Custom\Helper\EmailValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\VendorRegisterRequest;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/vendor/login';
    /**
     * @var VendorService
     */
    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->middleware('guest:vendor')->except('logout');
        $this->vendorService = $vendorService;
    }

    public function showRegisterForm()
    {
        return view('vendor.auth.register');
    }

    public function register(VendorRegisterRequest $request)
    {

        if ($request->email) {
            $response = new EmailValidator($request->email);

            if (!$response->validate()) {
                request()->session()->flash('failure_message', 'Invalid email.');
            }
        }

        $vendor = new Vendor;
        $vendor->business_name = $request->businessName;
        $vendor->first_name = $request->firstName;
        $vendor->last_name = $request->lastName;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->partnership_type = $request->partnershipType;
        $vendor->type = $request->type;
        $vendor->heard_from = $request->heardFrom;
        $vendor->password = bcrypt($request->password);
        $vendor->city = $request->city;
        $vendor->address = $request->address;
        $vendor->email_token = str_random(10);

        $mainRole        = Role::where('name', 'head_office')->first();


        if ($vendor->save()) {
            $vendor->roles()->attach($mainRole);
            try {
                // Mail::to($vendor)->send(new VendorVerifyEmail($vendor->email_token));
            } catch (\Throwable $th) {
                //throw $th;
            }
            $this->guard()->login($vendor);
            request()->session()->flash('success_message', 'You are registered. Please verify your email and you will be notified after your business account is approved.');

            return redirect($this->redirectPath());
        }

        request()->session()->flash('failure_message', 'We can\'t process this right now.');
    }



    // Get the user who has the same token and change his/her status to verified i.e. 1
    public function verify($token)
    {
        Vendor::where('email_token', $token)->firstOrFail()->verifyEmail();
        return redirect('/vendor/login')->with('success_message', 'Your email is verified.');
    }
}
