<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent builder provider.
    |
    | All authentication drivers have a builder provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your builder's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver'   => 'jwt',
            'provider' => 'users',
        ],

        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],

        'admin-api' => [
            'driver'   => 'jwt',
            'provider' => 'admins',
        ],

        'vendor' => [
            'driver'   => 'session',
            'provider' => 'vendors',
        ],

        'vendor-api' => [
            'driver'   => 'jwt',
            'provider' => 'vendors',
        ],

        'driver-api' => [
            'driver'   => 'jwt',
            'provider' => 'drivers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a builder provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your builder's data.
    |
    | If you have multiple builder tables or models you may configure multiple
    | sources which represent each builder / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver'  => 'eloquent',
            'builder' => App\User::class,
            'model'   => App\User::class,
        ],

        'admins' => [
            'driver'  => 'eloquent',
            'builder' => App\Admin::class,
            'model'   => App\Admin::class,
        ],

        'vendors' => [
            'driver'  => 'eloquent',
            'builder' => App\Vendor::class,
            'model'   => App\Vendor::class,
        ],

        'drivers' => [
            'driver'  => 'eloquent',
            'builder' => App\Driver::class,
            'model'   => App\Driver::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one builder table or builder in the application and you want to have
    | separate password reset settings based on the specific builder types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_resets',
            'expire'   => 30,
        ],

        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_resets',
            'expire'   => 30,
        ],

        'vendors' => [
            'provider' => 'vendors',
            'table'    => 'password_resets',
            'expire'   => 30,
        ],

        'drivers' => [
            'provider' => 'drivers',
            'table'    => 'password_resets',
            'expire'   => 30,
        ],
    ],

];
