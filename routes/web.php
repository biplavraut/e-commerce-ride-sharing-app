<?php

namespace App;

// use Illuminate\Support\Facades\Auth;

use App\User;
use Carbon\Carbon;
use App\DefaultConf;
use App\Mail\VerifyEmail;
use App\Mail\TestAmazonSes;
use App\Exports\RiderExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Auth::routes();

Route::post('test-url', 'CommonController@test')->name('test');

Route::get('images/{imageName}/{size?}', 'CommonController@getImage')->name('get-image');

// it verifies the user based on his/her email
Route::get('register/verify/{token}', 'Auth\RegisterController@verify')->name('verify');


// social login starts
Route::get('login/facebook', 'SocialLoginController@redirectToFacebook')->name('facebookLogin');
Route::get('login/facebook/callback', 'SocialLoginController@getFacebookCallback');
Route::get('login/google', 'SocialLoginController@redirectToGoogle')->name('googleLogin');
Route::get('login/google/callback', 'SocialLoginController@getGoogleCallback');
Route::get('a-logout', 'SocialLoginController@logout')->name('sLogout');

Route::get('/email', function () {
    return redirect('https://gogo20-com.awsapps.com/mail');
});


Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('optimize:clear');
    return "Config,Cache and Optimize is cleared";
});

Route::get('/mail', function () {
    // Mail::to("sunbi.mac@gmail.com")->send(new VerifyEmail("dsfdsfds"));
});
