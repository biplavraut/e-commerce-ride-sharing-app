<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'VendorController@register');
Route::post('login', 'VendorController@login');
Route::post('refresh-access-token', 'VendorController@refreshAccessToken');

// OTPS
Route::get('send-otp', 'MiscController@sendOtp')->name('send-otp');
Route::get('verify-otp', 'MiscController@verifyOtp')->name('verify-otp');
Route::get('forget-password-otp', 'MiscController@ForgetPasswordOtp')->name('forget-password-otp');
Route::post('forget-password-update', 'MiscController@updateForgetPassword');

Route::middleware('apiAuth')->group(function () {
    Route::post('logout', 'VendorController@logout');

    Route::post('verify-email', 'VendorController@verifyEmail');

    Route::post('update-password', 'MiscController@updatePassword');

    Route::prefix('order')->group(function () {
        Route::get('/accept-order', 'OrderController@acceptOrder');
        Route::get('/accepted-order', 'OrderController@acceptedOrderList');
        Route::get('/returned-order', 'OrderController@returnedOrderList');
        Route::post('/returned-order', 'OrderController@updateReturnedOrder');
        //Added By MacAlistair1
        Route::post('/cancel-order', 'OrderController@cancelOrder');
        Route::post('/delete-item', 'OrderController@deleteItem');
        Route::post('/update-item', 'OrderController@updateItem');
        Route::get('/takeaway-list', 'OrderController@takeAwayList');
        Route::post('/takeaway-delivered', 'OrderController@markAsDelivered');
        Route::post('/service-delivered', 'OrderController@serviceOrderDelivered');
    });
    Route::get('order', 'OrderController@orderList');
    Route::get('order-detail', 'OrderController@orderDetail');

    Route::get('settlement', 'SettlementController@settlement');

    Route::get('/gogo-academy', 'MiscController@academy');

    //custom-notification
    Route::get('/my-notification', 'NotificationController@customNotification');

    Route::get('/dinein-list', 'MiscController@dineInList');
    Route::post('/dinein-status', 'MiscController@dineInStatus');
});


//Custom Data
Route::get('/default-data', 'MiscController@defaultData');

Route::get('/gogo-ads', 'MiscController@ads');
Route::get('/road-block-message', 'MiscController@roadBlockMessage')->name('road-block-message');
