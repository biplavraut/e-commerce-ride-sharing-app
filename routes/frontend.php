<?php

use Illuminate\Support\Facades\Route;

Route::get('/download', 'WebsiteController@download')->name('download');
Route::get('/', 'WebsiteController@index')->name('home');
Route::get('/faq', 'WebsiteController@faq')->name('home');
// Route::get('/delivery-pilot', 'WebsiteController@deliveryPilot')->name('pilot');
Route::get('/career', 'WebsiteController@career')->name('career');
Route::get('/terms-and-condition/{type?}', 'WebsiteController@tac')->name('tac');
Route::get('/privacy-policy', 'WebsiteController@policy')->name('policy');
// Location pointing
Route::get('/getLocation', 'WebsiteController@showLocation')->name('LocationShow');
Route::name('frontend.')->group(function () {
});
