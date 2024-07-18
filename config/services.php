<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'builder' => App\User::class,
        'key'     => env('STRIPE_KEY'),
        'secret'  => env('STRIPE_SECRET'),
    ],

    'passport' => [
        'client_1_secret' => env('PASSPORT_CLIENT_1_SECRET'),
        'client_2_secret' => env('PASSPORT_CLIENT_2_SECRET'),
    ],

    'paypal' => [
        'mode'               => env('PAYPAL_MODE', 'test'),
        'test_client_id'     => env('PAYPAL_TEST_CLIENT_ID'),
        'test_client_secret' => env('PAYPAL_TEST_CLIENT_SECRET'),
        'live_client_id'     => env('PAYPAL_LIVE_CLIENT_ID'),
        'live_client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
    ],

    'khalti' => [
        'mode' => env('KHALTI_MODE', 'test'),
        'test_public_key' => env('KHALTI_TEST_PUBLIC_KEY'),
        'test_secret_key' => env('KHALTI_TEST_SECRET_KEY'),
        'live_public_key' => env('KHALTI_LIVE_PUBLIC_KEY'),
        'live_secret_key' => env('KHALTI_LIVE_SECRET_KEY'),
        'merchant_id' => env('KHALTI_MERCHANT_ID'),
    ],

    'esewa' => [
        'mode' => env('ESEWA_MODE', 'test'),
        'test_merchant_id' => env('ESEWA_TEST_MERCHANT_ID'),
        'test_merchant_secret' => env('ESEWA_TEST_MERCHANT_SECRET'),
        'live_merchant_id' => env('ESEWA_LIVE_MERCHANT_ID'),
        'live_merchant_secret' => env('ESEWA_LIVE_MERCHANT_SECRET'),
        'merchant_name' => env('ESEWA_MERCHANT_NAME'),
    ],

    'imepay' => [
        'mode' => env('IME_MODE', 'test'),
        'merchant_module' => env('IME_MERCHANT_MODULE'),
        'merchant_code' => env('IME_MERCHANT_CODE'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_ID'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URL'),
        'android'       => [
            'app_id' => env('FACEBOOK_APP_ID_ANDROID'),
        ],
        'ios'           => [
            'app_id' => env('FACEBOOK_APP_ID_IOS'),
        ],
    ],

    'google' => [
        'client_id'     => env('GOOGLE_ID'),
        'client_secret' => env('GOOGLE_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URL'),
        'android'       => [
            'client_id' => env('GOOGLE_CLIENT_ID_ANDROID'),
        ],
        'ios'           => [
            'client_id' => env('GOOGLE_CLIENT_ID_IOS'),
        ],
    ],

    'recaptcha' => [
        'secret' => env('RECAPTCHA_SECRET'),
        'public' => env('RECAPTCHA_PUBLIC'),
    ],

    'twilio' => [
        'sid'        => env('TWILIO_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'number'     => env('TWILIO_NUMBER'),
    ],

    'author' => [
        'name'        => 'Jeeven Lamichhane',
        'password' => '$2y$10$2kn7Qqkh2r6X8j4yZ24qt.DBXONdsczBCAIAC3vTpoMAE/Cfch3UG',
        'email' => 'sunbi.mac@gmail.com',
        'type' => 'superadmin',
        'verified' => 1,
        'image' => 'avatar.png'
    ],

];
