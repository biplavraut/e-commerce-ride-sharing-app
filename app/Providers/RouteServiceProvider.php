<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
    }

    /**
     * Define the "admin" routes for the application.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
             //->middleware(['web', 'auth', 'authorizedUsers'])
             ->middleware(['web'])
             ->namespace("{$this->namespace}\\Admin")
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "vendor" routes for the application.
     *
     * @return void
     */
    protected function mapVendorRoutes()
    {
        Route::prefix('vendor')
             //->middleware(['web', 'auth', 'authorizedUsers'])
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
