<?php

use App\Mail\VerifyEmail;
use App\GlobalNotification;
use App\Services\GlobalNotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::namespace('Auth')->name('admin.')->group(function () {
    Route::post('/logout', 'LoginController@logout')->name('logout');
    Route::get('/login', 'LoginController@showLoginFrom')->name('login.form');
    Route::post('/login', 'LoginController@login')->name('login');
});

Route::redirect('/', '/admin/v1', 301);

Route::middleware('auth:admin')->group(function () {
    Route::get('v1/{param1?}/{param2?}/{param3?}', 'DashboardController@index')->name('admin.dashboard');
    Route::get('/tt', 'DashboardController@ridersIncome');

    Route::post('/change-password', 'AdminController@changePassword')->name('user.password.change.store');
    Route::post('/update-profile', 'AdminController@updateProfile');
    Route::get('/get-support', 'AdminController@getSupport');

    Route::apiResource('/company', 'CompanyController', ['only' => ['update']]);

    Route::prefix('/user')->group(function () {
        Route::get('/get-data', 'UserController@search');
        Route::get('/state', 'UserController@handleTabState');
        Route::post('/changepassword', 'UserController@changepassword');
        Route::get('/block', 'UserController@blockState');

        Route::get('/elite-request-list', 'UserController@eliteRequestList');
        Route::get('/elite-state', 'UserController@eliteState');
        Route::get('/elite-request-delete', 'UserController@eliteRequestDelete');
        Route::post('custom-notification', 'UserController@customNotification');
    });
    Route::apiResource('/user', 'UserController');

    Route::prefix('/super-user')->group(function () {
        Route::get('/get-data', 'SuperUserController@search');
        Route::get('/unverify', 'SuperUserController@markAsUnverified');
        Route::post('/update-password/{id}', 'SuperUserController@updatePassword');
    });
    Route::apiResource('/super-user', 'SuperUserController');

    Route::prefix('/service')->group(function () {
        Route::post('/change-order', 'CategoryController@changeOrder');
        Route::get('/get-all', 'CategoryController@getAll');
        Route::get('/excel-export', 'CategoryController@excelExport');
    });
    Route::apiResource('/service', 'CategoryController');

    Route::prefix('/product-category')->group(function () {
        Route::get('/get-root', 'ProductCategoryController@getRoot');
        Route::get('/by-category', 'ProductCategoryController@byRoot');
        Route::get('/get-data', 'ProductCategoryController@search');
        Route::get('/get-all', 'ProductCategoryController@getAll');
        Route::get('/excel-export', 'ProductCategoryController@excelExport');
        Route::post('/excel-import', 'ProductCategoryController@importCategory');
        Route::post('/change-order', 'ProductCategoryController@changeOrder');
        Route::get('/child-list', 'ProductCategoryController@getChildList');
    });
    Route::apiResource('/product-category', 'ProductCategoryController');

    Route::prefix('/unit')->group(function () {
        Route::post('/excel-import', 'UnitController@import');
        Route::get('/get-data', 'UnitController@search');
    });
    Route::apiResource('/unit', 'UnitController');

    Route::prefix('/vendor')->group(function () {
        Route::get('/list', 'VendorController@getVendorList');
        Route::get('/by-service', 'VendorController@byService');
        Route::get('/get-vendors', 'VendorController@getVendor');
        Route::get('/verify-me', 'VendorController@verify');
        Route::get('/action/{id}', 'VendorController@actionPerform');
        Route::get('/disable/{id}', 'VendorController@disableVendor');
        Route::get('/open-close-time/{id}', 'VendorController@openClose');
        Route::get('/options', 'VendorController@options');
    });
    Route::apiResource('/vendor', 'VendorController');

    Route::prefix('/product')->group(function () {
        Route::get('/get-subcategory', 'ProductCategoryController@subcategory');
        Route::get('/get-units', 'ProductController@getUnits');
        Route::get('/options', 'ProductController@options');
        Route::get('/get-tags', 'ProductController@getTags');
        Route::get('/get-products', 'ProductController@getProducts');
        Route::get('/verify-product', 'ProductController@verifyNow');
        Route::get('/verify-multiple', 'ProductController@verifyMultiple');
        Route::get('/verified-only', 'ProductController@verifiedOnly');
        Route::post('/delete-image', 'ProductController@deleteProductImage');
        Route::post('/excel-import', 'ProductController@excelImport');
        Route::get('/update-list', 'ProductController@updateList');
        Route::get('/revert-vendor-update', 'ProductController@revertUpdate');
        Route::get('/update-change', 'ProductController@updateVendorChanges');
    });
    Route::apiResource('/product', 'ProductController');

    Route::prefix('/driver')->group(function () {
        Route::post('/update-expiry-date', 'DriverController@updateExpiry');
        Route::get('/get-drivers', 'DriverController@getDrivers');
        Route::get('/verify-driver', 'DriverController@verifyNow');
        Route::get('/partially-verify-driver', 'DriverController@partiallyVerifyNow');
        Route::get('/verified-only', 'DriverController@verifiedOnly');
        Route::post('/change-associated-status', 'DriverController@changeAssocaitedStatus');
        Route::get('/blocked-only', 'DriverController@blockedOnly');
        Route::get('/blacklisted-only', 'DriverController@blacklistedOnly');
        Route::get('/active-only', 'DriverController@activeOnly');
        Route::get('/associated-only', 'DriverController@assocatedRider');
        Route::post('/clear-block-blacklist', 'DriverController@clearBlockBlackList');
        Route::get('/subscription-list', 'DriverController@subscriptionList');
        Route::post('/change-subscription-type', 'DriverController@updateInterestedIn');
        Route::get('/incomplete', 'DriverController@incompleteDocumentRiderList');
        Route::post('/update-license', 'DriverController@updateLicense');
        Route::post('/update-bluebook', 'DriverController@updateBluebook');
        Route::get('/block', 'DriverController@block');
        Route::post('/switch-subscription-type', 'DriverController@switchSubscription');
        Route::get('/partial-verified', 'DriverController@partiallyVerifiedRiderList');
    });
    Route::apiResource('/driver', 'DriverController');


    Route::prefix('/delivery-driver')->group(function () {
        Route::post('/update-expiry-date', 'DeliveryRiderController@updateExpiry');
        Route::get('/get-drivers', 'DeliveryRiderController@getDrivers');
        Route::get('/verify-driver', 'DeliveryRiderController@verifyNow');
        Route::get('/verified-only', 'DeliveryRiderController@verifiedOnly');
        Route::post('/change-associated-status', 'DeliveryRiderController@changeAssocaitedStatus');
        Route::get('/blocked-only', 'DeliveryRiderController@blockedOnly');
        Route::get('/blacklisted-only', 'DeliveryRiderController@blacklistedOnly');
        Route::get('/active-only', 'DeliveryRiderController@activeOnly');
        Route::get('/associated-only', 'DeliveryRiderController@assocatedRider');
        Route::post('/clear-block-blacklist', 'DeliveryRiderController@clearBlockBlackList');
        Route::get('/subscription-list', 'DeliveryRiderController@subscriptionList');
        Route::post('/change-subscription-type', 'DeliveryRiderController@updateInterestedIn');
        Route::get('/incomplete', 'DeliveryRiderController@incompleteDocumentRiderList');
        Route::post('/update-license', 'DeliveryRiderController@updateLicense');
        Route::post('/update-bluebook', 'DeliveryRiderController@updateBluebook');
        Route::get('/block', 'DeliveryRiderController@block');
    });
    Route::apiResource('/delivery-driver', 'DeliveryRiderController');


    //Riding Part Starts From Here
    Route::prefix('/premium-place')->group(function () {
        Route::post('/excel-import', 'PremiumPlaceController@import');
        Route::get('/get-place', 'PremiumPlaceController@getPlaces');
        Route::get('/get-data', 'PremiumPlaceController@search');
    });
    Route::apiResource('/premium-place', 'PremiumPlaceController');

    Route::prefix('/riding-fare')->group(function () {
        Route::get('/get-data', 'RidingFareController@search');
        Route::get('/delete-surge', 'RidingFareController@deleteSurge');
    });
    Route::apiResource('/riding-fare', 'RidingFareController');

    Route::post('/rental-package/excel-import', 'RentalPackageController@import');
    Route::get('/rental-package/get-data', 'RentalPackageController@search');
    Route::apiResource('/rental-package', 'RentalPackageController');

    Route::get('/rental-trip/get-rider', 'RentalTripController@getRiders');
    Route::post('/rental-trip/update-rider', 'RentalTripController@updateRider');
    Route::get('/rental-trip/get-trip', 'RentalTripController@getTrips');
    Route::apiResource('/rental-trip', 'RentalTripController');

    Route::get('/outstation-trip/get-rider', 'OutstationTripController@getRiders');
    Route::post('/outstation-trip/update-rider', 'OutstationTripController@updateRider');
    Route::get('/outstation-trip/get-trip', 'OutstationTripController@getTrips');
    Route::apiResource('/outstation-trip', 'OutstationTripController');


    ///Riding Part Ends From Here

    //Ecommerce
    Route::get('/launchpad-category/get-data', 'LaunchpadCategoryController@search');
    Route::post('/launchpad-category/change-order', 'LaunchpadCategoryController@changeOrder');
    Route::get('/launchpad-category/get-all', 'LaunchpadCategoryController@getAll');

    Route::apiResource('/launchpad-category', 'LaunchpadCategoryController');

    Route::post('/launchpad/change-order', 'LaunchpadController@changeOrder');
    Route::get('/launchpad/by-category', 'LaunchpadController@byCategory');
    Route::get('/launchpad/get-data', 'LaunchpadController@search');
    Route::apiResource('/launchpad', 'LaunchpadController');

    Route::get('/slider/by-service', 'SliderController@byService');
    Route::get('/slider/get-data', 'SliderController@search');
    Route::apiResource('/slider', 'SliderController');


    Route::post('/product-option-category/change-order', 'ProductOptionCategoryController@changeOrder');
    Route::get('/product-option-category/by-service', 'ProductOptionCategoryController@byService');
    Route::apiResource('/product-option-category', 'ProductOptionCategoryController');


    Route::get('/product-option/category-list', 'ProductOptionSortController@optionCategoryList');
    Route::post('/product-option/change-order', 'ProductOptionSortController@changeOrder');
    Route::get('/product-option/by-service', 'ProductOptionSortController@byService');
    Route::apiResource('/product-option', 'ProductOptionSortController');

    //newly added
    Route::post('/vendor-option-category/change-order', 'VendorOptionCategoryController@changeOrder');
    Route::get('/vendor-option-category/by-service', 'VendorOptionCategoryController@byService');
    Route::apiResource('/vendor-option-category', 'VendorOptionCategoryController');

    Route::get('/vendor-option/category-list', 'VendorOptionSortController@optionCategoryList');
    Route::post('/vendor-option/change-order', 'VendorOptionSortController@changeOrder');
    Route::get('/vendor-option/by-service', 'VendorOptionSortController@byService');
    Route::apiResource('/vendor-option', 'VendorOptionSortController');


    Route::prefix('/order')->group(function () {
        Route::get('/get-data', 'OrderController@search');
        Route::get('/get-status', 'OrderController@status');
        Route::post('/dispatch', 'OrderController@dispatchOrder');
    });
    Route::post('/assign-delivery', 'OrderController@assignDelivery');
    Route::apiResource('/order', 'OrderController');
    Route::post('/order-feedback/respond', 'OrderFeedbackController@updateRespond');
    Route::apiResource('/order-feedback', 'OrderFeedbackController');


    //trip
    Route::get('/trip/locate/{riderId}', 'TripController@locateDriver');
    Route::get('/trip/solve-case/{tripId}', 'TripController@updateTripIssue');
    Route::get('/trip/get-accident-trips', 'TripController@getAccidentTrips');
    Route::get('/trip/get-dispute-trips', 'TripController@getDisputeTrips');
    Route::get('/trip/get-paused-trips', 'TripController@getPausedTrips');
    Route::get('/trip/get-completed-trips', 'TripController@getCompletedTrips');
    Route::get('/trip/get-schedule-trips', 'TripController@getScheduleTrips');
    Route::get('/trip/get-data', 'TripController@search');
    Route::apiResource('/trip', 'TripController');

    Route::get('/delivery/locate/{riderId}', 'TripController@locateDriver');
    Route::get('/delivery/get-data', 'DeliveryController@search');
    Route::apiResource('/delivery', 'DeliveryController');

    // Gogo's Rider list
    Route::get('/get-rider', 'DriverController@assocatedRider');

    //Coupon Codes
    Route::get('/coupon/get-data', 'CouponCodeController@search');
    Route::apiResource('/coupon', 'CouponCodeController');

    //Donation list
    Route::get('/donation/get-data', 'DonationController@search');
    Route::apiResource('/donation', 'DonationController');

    //FAQ list
    Route::get('/faq/get-data', 'FaqController@search');
    Route::apiResource('/faq', 'FaqController');

    //Rider Payment Settlement
    Route::get('/payment-settlement/get-data', 'PaymentController@search');
    Route::apiResource('/payment-settlement', 'PaymentController');


    //Global Notification
    Route::get('/global-notification/send-now/{id}', 'GlobalNotificationController@sendNow');
    Route::get('/global-notification/get-data', 'GlobalNotificationController@search');
    Route::apiResource('/global-notification', 'GlobalNotificationController');


    //Gogo ads
    Route::get('/ad/get-data', 'GogoAdController@search');
    Route::apiResource('/ad', 'GogoAdController');


    //Road Block Message
    Route::get('/road-block/get-data', 'RoadBlockMessageController@search');
    Route::apiResource('/road-block', 'RoadBlockMessageController');

    Route::apiResource('/discount', 'DiscountController');
    Route::apiResource('/items', 'ItemsController');

    Route::get('/send', 'OrderController@searchSendData');
    Route::get('/pool', 'OrderController@searchPoolData');
    // Route::post('order/dispatch', 'OrderController@dispatchOrder');
    // Route::post('assign-delivery', 'OrderController@assignDelivery');

    //PaymentLog 
    Route::get('/payment-log/get-data', 'PaymentLogController@search');
    Route::get('/payment-log/range', 'PaymentLogController@range');
    Route::apiResource('/payment-log', 'PaymentLogController');

    Route::get('/khalti-log', 'DigitalPaymentController@khalti');
    Route::get('/esewa-log', 'DigitalPaymentController@esewa');

    //Settlement
    #1 Refund
    Route::get('/refund-settlement', 'SettlementController@refundList');
    Route::post('/refund-settlement/update', 'SettlementController@refundUpdate');

    #2 Vendor
    Route::get('/vendor-settle/{time}', 'SettlementController@vendorList');
    Route::post('/vendor-settle/update', 'SettlementController@vendorUpdate');

    //Default Conf
    Route::get('/default-conf', 'DashboardController@globalConf');
    Route::apiResource('/configuration', 'DefaultConfController');

    Route::get('/partner/get-data', 'PartnerController@search');
    Route::get('/partner/branches/{parentId}', 'PartnerController@branches');
    Route::post('/partner/change-order', 'PartnerController@changeOrder');
    Route::apiResource('/partner', 'PartnerController');

    Route::get('/reset-password/get-data', 'PasswordResetController@search');
    Route::get('/reset-password/action', 'PasswordResetController@action');

    //Subscription Package
    Route::get('/package/get-data', 'SubscriptionPackageController@search');
    Route::apiResource('/package', 'SubscriptionPackageController');

    Route::get('/rider-log/get-data', 'InHouseRiderLogController@search');
    Route::post('/rider-log/receive', 'InHouseRiderLogController@markAsReceived');
    Route::apiResource('/rider-log', 'InHouseRiderLogController');

    Route::get('/vendor-review/get-data', 'VendorRatingController@search');
    Route::get('/vendor-review/verify', 'VendorRatingController@verify');
    Route::apiResource('/vendor-review', 'VendorRatingController');

    //Layout Manager
    Route::post('/layout-manager/change-order', 'LayoutManagerController@changeOrder');
    Route::get('/layout-manager/by-service', 'LayoutManagerController@byService');
    Route::get('/layout-manager/model-list', 'LayoutManagerController@modelList');
    Route::get('/layout-manager/model-id-list', 'LayoutManagerController@modelIdList');
    Route::apiResource('/layout-manager', 'LayoutManagerController');


    Route::prefix('/delivery-junction')->group(function () {
        Route::get('/get-data', 'DeliveryJunctionController@search');
        Route::get('/list', 'DeliveryJunctionController@list');
    });
    Route::apiResource('/delivery-junction', 'DeliveryJunctionController');

    Route::prefix('/campaign')->group(function () {
        Route::get('/get-data', 'CampaignController@search');
        Route::get('/user-list', 'CampaignController@userSearch');
    });
    Route::apiResource('/campaign', 'CampaignController');



    Route::prefix('/voucher')->group(function () {
        Route::get('/get-data', 'VoucherController@search');
        Route::get('/user-list', 'VoucherController@userSearch');
    });
    Route::apiResource('/voucher', 'VoucherController');


    Route::prefix('/notification')->group(function () {
        Route::get('/', 'NotificationController@index');
        Route::get('/latest', 'NotificationController@latestNotifications');
        Route::get('/{id}/mark-as-read', 'NotificationController@markAsRead');
    });

    //Vendor Discount
    Route::get('/vendor-discount/find-vendor', 'VendorDiscountController@findVendor');
    Route::apiResource('/vendor-discount', 'VendorDiscountController');


    Route::prefix('/additional-service')->group(function () {
        Route::get('/get-data', 'AdditionServiceController@search');
        Route::post('/change-order', 'AdditionServiceController@changeOrder');
    });
    Route::apiResource('/additional-service', 'AdditionServiceController');



    Route::prefix('/academy-slider')->group(function () {
        Route::get('/get-data', 'AcademySliderController@search');
    });
    Route::apiResource('/academy-slider', 'AcademySliderController');


    Route::prefix('/academy-content')->group(function () {
        Route::get('/get-data', 'AcademyContentController@search');
    });
    Route::apiResource('/academy-content', 'AcademyContentController');


    Route::get('dinein/update-status', 'DineinController@updateStatus');
    Route::apiResource('dinein', 'DineinController');

    Route::get('/website-slider/get-data', 'WebsiteSliderController@search');
    Route::post('/website-slider/change-order', 'WebsiteSliderController@changeOrder');
    Route::apiResource('/website-slider', 'WebsiteSliderController');

    /*......................................................*/
    // Advance Settlement Routes
    Route::get('/advancesettlement', 'SettlementController@listAdvanceSettlement');
    Route::post('/vendor-advance-settlement/add', 'SettlementController@addVendorAdvanceSettlement');
    Route::get('/vendor-settled-report', 'SettlementController@listVendorSettled');

    // Report Routes

    Route::prefix('/report')->group(function () {
        Route::get('/report-dashboard', 'ReportController@reportDashboard');
        Route::get('/app-user-report', 'ReportController@appUserReport');
        Route::get('/app-user-transactions', 'ReportController@appUserTransaction');
        Route::get('/top-user-transactions', 'ReportController@topUserTransaction');
        Route::get('/vendor-report', 'ReportController@vendorReport');
        Route::get('/rider-report', 'ReportController@riderReport');
        Route::get('/order-report', 'ReportController@orderReport');
        Route::get('/trip-report', 'ReportController@tripReport');

        Route::prefix('/referral-report')->group(function () {
            Route::get('/referred-user', 'ReportController@refferedUser');
            Route::get('/top-user-referrar', 'ReportController@topUserReferrar');
            Route::get('/referred-riders', 'ReportController@refferedRider');
            Route::get('/top-rider-referrar', 'ReportController@topRiderReferrar');
        });
        Route::get('/referral-report', 'ReportController@refferReport');
    });
    Route::apiResource('/report', 'ReportController', ['only' => ['index']]);

    // Deals
    Route::get('/deal/get-data', 'DealController@search');
    Route::get('/deal-products/{id}', 'DealController@loadDealDetail');
    Route::get('/find-product', 'DealController@findProduct');
    Route::post('/deal-products/add', 'DealController@addProduct');
    Route::delete('/delete-deal-product/{id}', 'DealController@deleteDealProduct');
    Route::post('/deal/change-order', 'DealController@changeOrder');
    Route::apiResource('/deal', 'DealController');

    // Wallet
    Route::post('/elite-user-wallet/add', 'WalletController@addPoint');

    //Utility Coupon Codes
    Route::get('/utility-coupon/get-data', 'UtilityCouponCodeController@search');
    Route::apiResource('/utility-coupon', 'UtilityCouponCodeController');

    //Utility Voucher Codes
    Route::prefix('/utility-voucher')->group(function () {
        Route::get('/get-data', 'UtilityVoucherController@search');
        Route::get('/user-list', 'UtilityVoucherController@userSearch');
    });
    Route::apiResource('/utility-voucher', 'UtilityVoucherController');

    // Order Offer Configuration
    Route::apiResource('/order-offer', 'OrderOfferConfController');

    // Ride Offer Configuration
    Route::apiResource('/ride-offer', 'RideOfferConfController');

    // Order Return
    Route::apiResource('/order-return', 'OrderReturnController');

    Route::post('/change-return-status', 'OrderReturnController@changeReturnStatus');
    Route::post('/resolve-return', 'OrderReturnController@resolveReturn');

    // Order Detail
    Route::get('/order-detail/{orderId}', 'OrderController@orderDetail');

    // Prescription

    Route::get('prescription-request', 'PrescriptionController@list');
    Route::get('prescription-request/update-status', 'PrescriptionController@updateStatus');
    Route::post('/assign-prescription', 'PrescriptionController@assignPharmacist');
    Route::post('/remark-prescription', 'PrescriptionController@remarkPrescription');
    // Wallet Advance Log'
    Route::get('wallet-log', 'WalletController@list');
    Route::get('wallet-log/get-data', 'WalletController@search');

    // Wallet Payment Log'
    Route::get('wallet-payment-log', 'WalletController@walletPaymentList');
    Route::get('wallet-payment-log/get-data', 'WalletController@walletPaymentSearch');

    // Cart
    Route::get('/cart-products/{id}', 'CartController@loadcart');
    Route::get('/find-product', 'CartController@findProduct');
    Route::post('/cart-products/add', 'CartController@addProduct');
    Route::delete('/delete-cart-product/{cartId}', 'CartController@deleteCartProduct');
    Route::get('/cart-notify-user/{userId}', 'CartController@notifyUser');
    Route::apiResource('/cart', 'CartController');

    Route::prefix('/hospital')->group(function () {
        Route::get('/get-data', 'HospitalController@search');
        Route::get('/list', 'HospitalController@list');
    });
    Route::apiResource('/hospital', 'HospitalController');
});


// Route::get('/send', function () {
//     $noti = GlobalNotification::find(20);

//     $service = new GlobalNotificationService();

//     $res =   $service->send($noti);

//     dd($res);
// });
