<?php

use App\Custom\Sms\AakashSms;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->name('vendor.')->group(function () {
    Route::post('logout', 'LoginController@logout')->name('logout');
    Route::get('/login', 'LoginController@showLoginFrom')->name('login.form');
    Route::post('login', 'LoginController@login')->name('login');

    // Route::get('/register', 'RegisterController@showRegisterForm')->name('register.form');
    // Route::post('register', 'RegisterController@register')->name('register');
    Route::get('register/verify/{token}', 'RegisterController@verify')->name('verify');
});

Route::redirect('/', '/vendor/v1', 301);
Route::middleware('auth:vendor')->group(function () {
    Route::get('v1/{param1?}/{param2?}/{param3?}', 'DashboardController@index')->name('vendor.dashboard');

    Route::post('change-password', 'VendorController@changePassword')->name('vendor.password.change.store');
    Route::post('update-profile', 'VendorController@updateProfile');

    Route::get('product-category/get-all', 'ProductController@getAllProductCategory');
    Route::get('product/get-units', 'ProductController@getUnits');
    Route::get('product/get-tags', 'ProductController@getTags');
    Route::get('product/excel-export', 'ProductController@excelExport');
    Route::post('product/excel-import', 'ProductController@excelImport');
    Route::get('product/get-subcategory', 'ProductController@subcategory');
    Route::post('product/delete-image', 'ProductController@deleteImage');
    Route::get('product/get-products', 'ProductController@getProducts');

    Route::get('product/get-reviews/get-data', 'ProductQAReviewController@searchReview');
    Route::get('product/get-reviews', 'ProductQAReviewController@listReviews');
    Route::delete('product/get-reviews/{id}', 'ProductQAReviewController@destroyReview');

    Route::get('product/get-qas/get-data', 'ProductQAReviewController@searchQas');
    Route::get('product/get-qas', 'ProductQAReviewController@listQAs');
    Route::get('product/verify-review', 'ProductQAReviewController@verifyReview');
    Route::post('product/answer-qa', 'ProductQAReviewController@answerQA');

    Route::get('products/options', 'ProductController@options');

    Route::apiResource('product', 'ProductController');

    Route::get('order/get-data', 'OrderController@search');
    Route::get('order/accept-order', 'OrderController@acceptOrder');

    Route::get('order/accepted-order', 'OrderController@acceptedOrderList');

    //Added By MacAlistair1
    Route::get('order/cancel-order', 'OrderController@cancelOrder');
    Route::get('order/delete-item', 'OrderController@deleteItem');
    Route::get('order/update-item', 'OrderController@updateItem');

    Route::get('order/takeaway-list', 'OrderController@takeAwayList');
    Route::get('order/deliver', 'OrderController@markAsDelivered');
    Route::apiResource('order', 'OrderController');

    Route::get('dinein/update-status', 'DineinController@updateStatus');
    Route::apiResource('dinein', 'DineinController');

    Route::post('firebase-token', 'VendorController@fcmToken');


    Route::prefix('notification')->group(function () {
        Route::get('/', 'NotificationController@index');
        Route::get('latest', 'NotificationController@latestNotifications');
        Route::get('{id}/mark-as-read', 'NotificationController@markAsRead');
    });
});
