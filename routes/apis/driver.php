<?php

use Illuminate\Support\Facades\Route;

Route::post('register', 'DriverController@register');
Route::post('registerweb', 'DriverController@registerweb');
Route::post('login', 'DriverController@login');
Route::post('refresh-access-token', 'DriverController@refreshAccessToken');
Route::get('register/verify/{token}', 'DriverController@verify')->name('driver-verify');


// OTPS
Route::get('send-otp', 'MiscController@sendOtp')->name('send-otp');
Route::get('verify-otp', 'MiscController@verifyOtp')->name('verify-otp');
Route::get('forget-password-otp', 'MiscController@ForgetPasswordOtp')->name('forget-password-otp');
Route::post('forget-password-update', 'MiscController@updateForgetPassword');

Route::middleware('apiAuth')->group(function () {

    Route::get('/generate-token', 'MiscController@generateToken');

    Route::post('logout', 'DriverController@logout');

    Route::post('verify-email', 'DriverController@verifyEmail');

    Route::post('update-password', 'MiscController@updatePassword');
    Route::post('update-profile', 'MiscController@updateProfile');

    Route::post('status-update', 'MiscController@status');

    Route::get('vehicle-document', 'DocumentController@index');
    Route::post('vehicle-document', 'DocumentController@store');

    Route::post('update-location', 'Ride\RideController@updateStatus');

    //Regular Trip Requests
    Route::post('accept-trip', 'Ride\RideController@acceptTrip');
    Route::post('trip-completed', 'Ride\RideController@tripCompleted');
    Route::post('trip-cancelled', 'Ride\RideController@tripCancelled');
    Route::post('arrived-at-location', 'Ride\RideController@arrivedToPickupPoint');
    Route::post('trip-started', 'Ride\RideController@tripStart');
    Route::post('trip-status', 'Ride\RideController@tripStatus');
    Route::get('trip-history', 'Ride\RideController@history');
    Route::post('reject-trip', 'Ride\RideController@reject');

    Route::get('my-schedule-trips', 'Ride\RideController@scheduleTrip');
    Route::get('my-ongoing-trips', 'Ride\RideController@ongoingTrip');


    //Trip COD Payment
    Route::post('trip-payment-received', 'Ride\RideController@tripPayment');


    Route::get('payment-settlement', 'PaymentSettlementController@index');
    Route::post('payment-settlement', 'PaymentSettlementController@store');


    //Delivery Requests
    Route::post('accept-delivery', 'DeliveryController@acceptDelivery');
    Route::post('delivery-completed', 'DeliveryController@deliveryCompleted');
    Route::post('rider-arrived-at-location', 'DeliveryController@arrivedToPickupPoint');
    Route::post('delivery-started', 'DeliveryController@deliveryStart');
    // Route::post('delivery-completed', 'DeliveryController@deliveryCompleted');
    Route::get('delivery-history', 'DeliveryController@history');
    Route::get('on-going-delivery', 'DeliveryController@ongoingDelivery');
    Route::post('delivery-payment-received', 'DeliveryController@deliveryPayment');

    // Prescription
    Route::get('assigned-prescription', 'PrescriptionController@assignedPrescription');
    Route::get('update-prescription-status', 'PrescriptionController@updatePrescriptionStatus');
    Route::get('health-vendors', 'PrescriptionController@healthVendors');
    Route::post('add-prescription-bill', 'PrescriptionController@addPrescriptionBill');
    Route::post('prescription-delivery-started', 'PrescriptionController@deliveryStart');
    Route::post('prescription-delivery-completed', 'PrescriptionController@prescriptionDeliveryCompleted');

    //Rental Trips
    Route::get('my-rental-trips', 'RentalTripController@list');
    Route::post('complete-rental-trip', 'RentalTripController@markAsCompleted');

    //Outstation Trips
    Route::get('my-outstation-trips', 'OutstationTripController@list');
    Route::post('complete-outstation-trip', 'OutstationTripController@markAsCompleted');



    Route::get('my-stat', 'DriverController@stat');
    Route::get('rider-leaderboard', 'DriverController@leaderBoard');

    Route::post('firebase-token', 'MiscController@fcmToken');


    //My Prefs
    Route::get('/my-prefs', 'PreferenceController@getPrefs');
    Route::post('/my-prefs', 'PreferenceController@prefs');

    //Home Address
    Route::get('/my-address', 'AddressController@getAddress');
    Route::post('/my-address', 'AddressController@setAddress');

    //For Gogo Commission Payment
    Route::post('pay-commission', 'PaymentSettlementController@pay');

    Route::get('/gogo-academy', 'MiscController@academy');


    //Custom Notification
    Route::get('/my-notification', 'NotificationController@customNotification');
});

Route::get('/gogo-ads', 'AdController@index');
Route::get('/road-block-message', 'MiscController@roadBlockMessage')->name('road-block-message');

Route::get('/global-conf', 'MiscController@globalConf');

//Address Listing
Route::get('/address-list', 'AddressController@list');
