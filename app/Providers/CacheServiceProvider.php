<?php

namespace App\Providers;

use App\Caches\CategoryCache;
use App\Caches\SocialCache;
use App\Services\CategoryService;
use App\Services\SocialService;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('category.cache', function ($app) {
        	return new CategoryCache(new CategoryService());
        	//return new CategoryService();
        });

	    $this->app->bind('social.cache', function ($app) {
		    return new SocialCache(new SocialService());
		    //return new SocialService();
	    });
    }
}
