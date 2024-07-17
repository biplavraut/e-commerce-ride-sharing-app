<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CommonController;
use App\Mail\VerifyEmail;
use App\Role;
use App\Services\UserService;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends CommonController
{
    protected $website = [];
    
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';
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
        $this->middleware('guest');
        $this->userService = $userService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/

        $normalRole        = Role::where('name', 'normal')->first();
        $user              = new User;
        $user->name        = $data['name'];
        $user->email       = $data['email'];
        $user->password    = bcrypt($data['password']);
        $user->email_token = str_random(10);
        $user->save();
        Mail::to($user)->send(new VerifyEmail($user));
        $user->roles()->attach($normalRole);
        request()->session()->flash('success_message', 'You are registered. Please verify your email to login.');

        return $user;
        // --------------------------------------------------------------------
    }

    // Get the user who has the same token and change his/her status to verified i.e. 1
    public function verify($token)
    {
        //$user = $this->userService->findOrFailBy('email_token', $token);
        //$this->userService->updateByModel($user, ['verified' => true, 'email_token' => null]);

        User::where('email_token', $token)->firstOrFail()->verifyEmail();

        return redirect('/')->with('success_message', 'Your email is verified. You can now login.');
    }

    /**
    * Show the application registration form.
    *
    * @return \Illuminate\Http\Response
    */
    public function showRegistrationForm()
    {
        $this->website['company'] = Company::findOrFail(1);
        return view("auth.register", $this->website);
    }
}
