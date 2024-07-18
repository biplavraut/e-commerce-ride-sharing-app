<?php

use Illuminate\Support\Facades\Route;

/**
 * User Registration and authentication routes
 * */
Route::prefix('auth')->group(function () {

    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
    Route::post('refresh-access-token', 'LoginController@refreshAccessToken');

    // Road Block Message
    Route::get('/road-block-message', 'MiscController@roadBlockMessage')->name('road-block-message');

    // OTPS
    // Route::group(['middleware' => 'throttle:60,1'], function () {
    Route::get('send-otp', 'MiscController@sendOtp')->name('send-otp');
    Route::get('verify-otp', 'MiscController@verifyOtp')->name('verify-otp');
    Route::get('forget-password-otp', 'MiscController@ForgetPasswordOtp')->name('forget-password-otp');
    Route::post('forget-password-update', 'MiscController@updateForgetPassword');
    // });


    Route::middleware('apiAuth')->group(function () {

        //Global Conf Data
        Route::get('/global-conf', 'MiscController@globalConf');

        Route::get('/latest-profile', 'MiscController@myProfile');
        Route::get('/generate-token', 'MiscController@generateToken');

        Route::post('logout', 'LoginController@logout');
        Route::post('verify-email', 'LoginController@verifyEmail');
        Route::post('update-password', 'MiscController@updatePassword');
        Route::post('update-profile', 'MiscController@updateProfile');

        //Ecommerce User Related
        Route::post('product-qa', 'ProductQAController@store');
        Route::post('product-rating-review', 'ProductQAController@ratingReview');
        Route::put('product-rating-review/{reviewId}', 'ProductQAController@updateRating');
        Route::get('/my-reviews', 'MiscController@myReviews');
        Route::get('/my-qas', 'MiscController@myQAs');
        Route::get('/my-past-purchase', 'MiscController@pastPurchase');

        Route::post('vendor-rating-review', 'ReviewRatingController@ratingReviewVendor');
        Route::put('vendor-rating-review/{reviewId}', 'ReviewRatingController@updateRatingVendor');

        //User Order
        Route::post('cancel-order', 'OrderController@cancelOrder');
        Route::post('order-feedback', 'OrderController@orderFeedback');
        Route::apiResource('orders', 'OrderController');
        Route::post('order-return', 'OrderReturnController@store');

        //UserCart
        Route::apiResource('carts', 'UserCartController');

        //Ride Part User Related
        Route::post('save-place', 'Ride\UserDataController@savePlace');
        Route::get('/saved-places', 'Ride\UserDataController@savedPlace');
        Route::post('request-trip', 'Ride\TripController@tripRequest');
        Route::post('trip/recall', 'Ride\TripController@recall');
        Route::post('trip-cancelled', 'Ride\TripController@tripCancelled');
        Route::post('trip-status', 'Ride\TripController@tripStatus');
        Route::post('rider-location', 'Ride\TripController@riderLocation');
        Route::post('/rate-the-rider', 'Ride\UserDataController@rateTheRider');
        Route::get('trip-history', 'Ride\TripController@history');

        Route::get('my-schedule-trips', 'Ride\TripController@scheduleTrip');
        Route::get('my-ongoing-trips', 'Ride\TripController@ongoingTrip');

        //Latest update

        //Trip Payment
        Route::post('trip-payment', 'Ride\TripController@tripPayment');

        //Ride Part User Related End

        //Other Rental and Outstation List
        Route::post('request-rental-trip', 'Ride\TripController@rentalTripRequest');
        Route::get('cancel-rental-trip', 'Ride\TripController@rentalTripCancel');
        Route::post('request-outstation-trip', 'Ride\TripController@outstationTripRequest');
        Route::get('cancel-outstation-trip', 'Ride\TripController@outstationTripCancel');
        Route::get('my-outstation-trips', 'Ride\OtherTripController@outstation');
        Route::get('my-rental-trips', 'Ride\OtherTripController@rental');

        //Coupon Code
        Route::get('check-coupon', 'CouponController@check');
        Route::post('firebase-token', 'MiscController@fcmToken');

        //Pool Request Available
        Route::get('/get-pool', 'PoolController@index')->name('GetPool');

        // Offerer Operations
        Route::get('/get-my-vehicle-list', 'PoolController@getmyvechiclelist')->name('Getmyvechiclelist');
        Route::post('/add-vehicle', 'PoolController@addVehicle')->name('AddVehicle');
        Route::post('/offer-pool', 'PoolController@store')->name('SetPool');
        Route::post('/cancel-pool', 'PoolController@cancel')->name('CancelPool');
        Route::post('/cancel-user-accept', 'PoolController@cancelUserAccept')->name('CancelUserPoolAccept');

        // Offer Accepter
        Route::post('/pool-action', 'PoolController@poolAction')->name('AcceptPool');
        Route::post('/cancel-my-pool-accept', 'PoolController@cancelUserAccept')->name('CancelMyPoolAccept');

        // Requester
        Route::post('/request-for-pool', 'PoolController@create')->name('RequestforPool');

        // send request
        Route::get('/send-items', 'SendController@getItems')->name('GetSendItems');
        Route::post('set-sends', 'SendController@setSends')->name('SetSends');
        Route::get('/get-send-request', 'SendController@listMySendOrder')->name('MySendOrder');

        // Wishlist
        Route::get('/my-wish-list', 'WishlistController@getlist')->name('MyWishList');
        Route::post('/add-to-wish-list', 'WishlistController@addToWishlist')->name('AddToMyWishList');
        Route::post('/remove-from-wish-list', 'WishlistController@removeFromWishlist')->name('RemoveFromWishList');

        //Custom Notification
        Route::get('/my-notification', 'NotificationController@customNotification');

        Route::post('/gogo-money', 'gogoMoneyController@load');
        Route::get('/transaction-history', 'gogoMoneyController@history');
        Route::get('/transaction-history-filter', 'gogoMoneyController@historyFilter');

        Route::post('/elite-request', 'EliteController@request');

        // Utility Check Coupon
        Route::get('utility-check-coupon', 'UtilityCouponController@check');

        // Prescription
        Route::post('upload-prescription', 'PrescriptionController@store');
        Route::get('my-prescription', 'PrescriptionController@prescriptionList');
        Route::post('cancel-prescription', 'PrescriptionController@cancelPrescription');

        // Hospitals Listing
        Route::get('hospital', 'PrescriptionController@listHospitals');
    });
});

Route::middleware('apiAuth')->group(function () {

    Route::get('/service-vendor-products', 'ServiceController@vendorProductsWithService');
    Route::get('/vendor-detail/{vendorId}', 'ServiceController@vendorDetail');
    Route::get('/vendor-categories', 'ServiceController@vendorCategories');
    Route::get('/vendor-category-products', 'ServiceController@vendorCategoryProducts');


    //Ecommerce Related

    //Service List
    Route::get('/service-list', 'ServiceController@list');

    //Layout Manager
    Route::get('/layout-list', 'LayoutManagerController@index');


    //Category & Products Apis
    Route::get('/service-products', 'ServiceController@serviceProducts');
    // Route::get('/service-category', 'ServiceController@category');
    // Route::get('/service-category', 'ServiceTestController@category');

    //new Category APi
    Route::get('/new-service-category/{serviceId}', 'ServiceController@newServiceCategory');


    // Route::get('/category-products', 'ProductCategoryController@categoryProducts');
    Route::get('/category-products', 'ProductCategoryController@categoryTestProducts');
    Route::get('/product', 'ProductController@getProduct');
    Route::get('/search-product', 'ProductController@searchProduct');
    Route::get('/vendor-products', 'ProductController@vendorProducts');

    Route::get('/services/{serviceId}/explore', 'ServiceController@explore');
    Route::get('/service-explore-products', 'ServiceController@exploreServiceProduct');
    Route::get('/services/{serviceId}/slider', 'ServiceController@sliders');

    Route::get('/service-vendor-option', 'ServiceController@serviceVendorOption');
    Route::get('/service-explore-vendors', 'ServiceController@exploreServiceVendorOption');

    //Testing
    // Route::get('/service-category-test', 'ServiceTestController@category');
    // Route::get('/category-test-products', 'ProductCategoryController@categoryTestProducts');

    //Ecommerce Related End

    //Launchpad
    Route::get('/launchpad', 'LaunchpadController@index');


    //Ride Part
    Route::get('/premium-place', 'Ride\CabController@premiumPlace');
    Route::get('/popular-places', 'Ride\CabController@popularPlaces');
    Route::get('/riding-fares', 'Ride\CabController@ridingFares');
    Route::get('/rental-packages', 'Ride\CabController@rentalPackages');

    //Ads
    Route::get('/gogo-ads/{serviceId?}', 'AdController@index');


    // Road Block Message
    Route::get('/road-block-message', 'MiscController@roadBlockMessage')->name('road-block-message');

    Route::get('/our-partner', 'PartnerController@index');
    Route::get('/branches/{parentId}', 'PartnerController@branches');

    Route::get('/gogo-academy', 'MiscController@academy');

    //Advanced Search 
    Route::get('/advanced-search', 'AdvancedSearchController@index');
    Route::get('/advanced-search/vendor', 'AdvancedSearchController@vendorList');
    Route::get('/advanced-search/product', 'AdvancedSearchController@productList');

    //Vendor Takeway dine service
    Route::get('/service-vendor-by-feature', 'ServiceController@serviceFeature');

    //Dine in form
    Route::post('/dinein-request', 'DineinController@storeRequest');
    Route::get('/dinein-list', 'DineinController@dineInFormList');
    Route::post('/dinein-complete', 'DineinController@dineInComplete');
});

Route::get('/default-conf', 'MiscController@default');

Route::get('/exclusive-service', 'MiscController@exclusiveServices');

Route::prefix('/pay-point')->middleware('apiAuth')->group(function () {
    Route::get('/package-list', 'PaypointController@packageList');
    Route::post('/topup', 'PaypointController@topup');
    Route::post('/package-purchase', 'PaypointController@packagePurchase');

    Route::get('/electricity-payment-check', 'PaypointController@electricityCheck');
    Route::post('/electricity-payment', 'PaypointController@electricity');

    Route::get('/isp-payment-check', 'PaypointController@ispCheck');
    Route::post('/isp-payment', 'PaypointController@ispPayment');


    Route::get('/khanepani-payment-check', 'PaypointController@khanepaniCheck');
    Route::post('/khanepani-payment', 'PaypointController@khanepaniPayment');
});

Route::get('/global-notification', 'NotificationController@globalList');

Route::get('/checkout-slider', 'MiscController@checkoutSlider');
