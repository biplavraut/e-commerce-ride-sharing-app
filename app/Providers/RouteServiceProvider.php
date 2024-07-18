<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route builder bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        header('Access-Control-Allow-Origin: *');

        $this->mapApiRoutes();

        $this->mapAdminRoutes();

        $this->mapVendorRoutes();

        $this->mapWebRoutes();

        $this->mapFrontendRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace("{$this->namespace}\\Api")
            ->group(base_path('routes/api.php'));

        Route::prefix('api/driver')
            ->middleware('api')
            ->namespace("{$this->namespace}\\Api\\Driver")
            ->group(base_path('routes/apis/driver.php'));

        Route::prefix('api/vendor')
            ->middleware('api')
            ->namespace("{$this->namespace}\\Api\\Vendor")
            ->group(base_path('routes/apis/vendor.php'));


        if (request()->expectsJson()) {
            Log::channel('api')->info("-----------API REQUEST START------------");
            Log::channel('api')->info("Request URI:" . request()->fullUrl());
            Log::channel('api')->info("Client IP:" . request()->getClientIp());
            if (auth()->guard('api')->user()) {
                Log::channel('api')->info("UserID:" . auth()->guard('api')->user());
            }
            Log::channel('api')->info("Request Header:" . json_encode(request()->header()));
            Log::channel('api')->info("Request Body:" . json_encode(request()->all()));
            Log::channel('api')->info("-----------API REQUEST END------------\n");
        }
    }

    /**
     * Define the "admin" routes for the application.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
            ->middleware(['web'])
            ->namespace("{$this->namespace}\\Admin")
            ->group(base_path('routes/admin.php'));


        if (request()->expectsJson()) {

            Log::channel('admin')->info("-----------ADMIN REQUEST START------------");
            Log::channel('admin')->info("Request URI:" . request()->fullUrl());
            Log::channel('admin')->info("Client IP:" . request()->getClientIp());
            if (auth()->user()) {
                Log::channel('admin')->info("UserID:" . auth()->user());
            }
            Log::channel('admin')->info("Request Header:" . json_encode(request()->header()));
            Log::channel('admin')->info("Request Body:" . json_encode(request()->all()));
            Log::channel('admin')->info("-----------ADMIN REQUEST END------------\n");
        }
    }
    /**
     * Define the "vendor" routes for the application.
     *
     * @return void
     */
    protected function mapVendorRoutes()
    {
        Route::prefix('vendor')
            ->middleware(['web'])
            ->namespace("{$this->namespace}\\Vendor")
            ->group(base_path('routes/vendor.php'));
    }

    /**
     * Define the "frontend" routes for the application.
     *
     * @return void
     */
    protected function mapFrontendRoutes()
    {
        Route::middleware('web')
            ->namespace("{$this->namespace}\\Frontend")
            ->group(base_path('routes/frontend.php'));
    }
}
