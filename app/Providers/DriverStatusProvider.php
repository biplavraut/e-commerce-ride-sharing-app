<?php

namespace App\Providers;

use App\DriverStatus;
use Illuminate\Support\ServiceProvider;

class DriverStatusProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $driverStatues = DriverStatus::where('status', '!=', 'ongoing')->Where('updated_at', '<', date('Y-m-d H:i:s', strtotime(now() . ' -5 minutes')))->get();
            foreach ($driverStatues as $key => $stat) {
                $stat->update(['status' => 'inactive']);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
