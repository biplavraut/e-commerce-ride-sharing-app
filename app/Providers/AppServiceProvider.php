<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Schema::defaultStringLength(191);
		Paginator::useBootstrapThree();

		// to log queries to laravel.log
		DB::listen(function ($query) {
			// Log::channel('dbquery')->info($query->sql);
		});

		if($this->app->environment('production')) {
			URL::forceScheme('https');
			$this->app['request']->server->set('HTTPS','on');	
		}
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{

	}
}
