<?php

namespace App\Providers;

use App\Otp;
use Illuminate\Support\ServiceProvider;

class OtpExpireServiceProvider extends ServiceProvider
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
            $expiredOtps = Otp::where('created_at', '<', date('Y-m-d H:i:s', strtotime(now() . ' -15 minutes')))->get();
            foreach ($expiredOtps as $key => $otp) {
                $otp->delete();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
