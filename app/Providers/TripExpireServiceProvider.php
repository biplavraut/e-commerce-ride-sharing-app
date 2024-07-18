<?php

namespace App\Providers;

use App\Trip;
use Firebase\FirebaseLib;
use App\Custom\PushNotification;
use App\Custom\Sms\Sparrow;
use Illuminate\Support\ServiceProvider;

class TripExpireServiceProvider extends ServiceProvider
{

    protected $firebase;
    protected $path = 'trips/';
    protected $riderLocation = 'riderLocations/';
    protected $riderPath = 'riderTrips/';
    protected $riderTempPath = 'riderTempTrips/';
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
            $expiredTrips = Trip::where('created_at', '<', date('Y-m-d H:i:s', strtotime(now() . ' -15 minutes')))->Where('status', 'pending')->get();
            foreach ($expiredTrips as $key => $trip) {
                $this->firebaseCleanUp($trip->id);
                $trip->delete();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $expiredSchedulesTrips = Trip::where('status', 'scheduled')->get();
            foreach ($expiredSchedulesTrips as $key => $scheduleTrip) {
                if (date('Y-m-d', strtotime($scheduleTrip->schedule->date)) <= date('Y-m-d') && date('H:i:s', strtotime($scheduleTrip->schedule->time . '15 minutes')) <= date('H:i:s')) {
                    $this->sendNotification($scheduleTrip);
                    $this->firebaseCleanUp($scheduleTrip->id);
                    $scheduleTrip->delete();
                }

                if (date('Y-m-d', strtotime($scheduleTrip->schedule->date)) == date('Y-m-d') && date('H:i:s', strtotime($scheduleTrip->schedule->time . '-15 minutes')) == date('H:i:s')) {
                    $message = "Your scheduled trip will start soon.\n";
                    $message .= ' gogo20 | Everyday Solution';
                    $this->sendSMS($scheduleTrip->user->phone, $message);
                    $this->sendSMS($scheduleTrip->driver->phone, $message);
                    $scheduleTrip->schedule->update(['notified' => 1]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function sendNotification($trip)
    {
        try {
            $notification = new PushNotification(
                [$trip->user->device->device_token, $trip->driver ? $trip->driver->device->device_token : ''],
                [
                    'title' => 'Schedule Trip',
                    'message' => 'Your scheduled trip has been auto cancelled.',
                    'type' => 'trip-cancelled',
                ]
            );
            $notification->send();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function firebaseCleanUp($tripId)
    {
        try {
            $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
            $this->firebase->delete(env('TRIP_PATH', 'trips/') . $tripId);

            $riderTemps = json_decode($this->firebase->get(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId));
            foreach ($riderTemps as $key => $value) {
                $this->firebase->delete(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId . '/' . $key);
                $this->firebase->delete(env('RIDER_TRIP_PATH', 'riderTrips/') . $value);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function sendSMS($phone, $message)
    {
        try {
            $sms = new Sparrow($phone, $message);
            $response = $sms->sendMessage();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
