<?php

namespace App\Providers;

use App\Trip;
use Illuminate\Support\ServiceProvider;
use Firebase\FirebaseLib;


class NoActionTripServiceProvider extends ServiceProvider
{

    protected $firebase;
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';
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
            $expiredTrips = Trip::where('created_at', '<', date('Y-m-d H:i:s', strtotime(now() . ' -40 seconds')))->where('status', 'pending')->get();
            foreach ($expiredTrips as $key => $trip) {
                $this->firebaseCleanUp($trip->id);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function firebaseCleanUp($tripId)
    {
        try {
            $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);

            $riderTemps = json_decode($this->firebase->get(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId));
            foreach ($riderTemps as $key => $value) {
                $this->firebase->delete(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId . '/' . $key);
                $this->firebase->delete(env('RIDER_TRIP_PATH', 'riderTrips/') . $value);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
