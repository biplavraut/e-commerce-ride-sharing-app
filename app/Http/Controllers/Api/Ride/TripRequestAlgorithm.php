<?php

namespace App\Http\Controllers\Api\Ride;

use App\Trip;
use App\User;
use App\DriverStatus;
use Firebase\FirebaseLib;
use App\Custom\PushNotification;
use App\Http\Resources\Api\Ride\TripResource;
use App\Http\Resources\Api\Ride\UserResource;
use App\Http\Resources\Api\Ride\DriverResource;

class TripRequestAlgorithm
{
    protected $firebase;
    protected $path = 'trips/';
    protected $riderPath = 'riderTrips/';
    protected $riderTempPath = 'riderTempTrips/';
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    protected $user;
    protected $trip;
    protected $preference;

    public function __construct(User $user, Trip $trip, $preference)
    {
        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
        $this->user = $user;
        $this->trip = $trip;
        $this->preference = $preference;
    }

    public function beginTransaction()
    {
        $nearByDistance = 5; // in km
        $availableNearByRiders = [];
        $filteredRiders = [];


        // Collecting active riders
        $activeRiders = DriverStatus::where('status', 'active')->where(function ($query) {
            $query->where('interest', 'rider');
            $query->orWhere('interest', 'Rider');
        })->get();

        foreach ($activeRiders as $key => $rider) {

            //getting air distance of rider and pickup point
            $distance =  number_format((float) getDistance($this->trip->from_lat, $this->trip->from_long, $rider->lat, $rider->long), 2, '.', '');

            if ($distance <= $nearByDistance) {
                $availableNearByRiders[] = $rider;
            }
        }
        //Add Preference
        if ($this->preference['childSeat'] == true ||  $this->preference['handiCapSupport'] == true ||  $this->preference['noSmoking'] == true) {
            foreach ($availableNearByRiders as $key => $nearByRider) {

                if ($nearByRider->driver->preference()
                    ->where('smoking', !$this->preference['noSmoking'])
                    ->where('child_seat', $this->preference['childSeat'])
                    ->where('handicap_support', $this->preference['handiCapSupport'])->first()
                ) {
                    $filteredRiders[] = $nearByRider;
                }
            }
        } else {
            $filteredRiders = $availableNearByRiders;
        }

        $this->firebaseRTDWorkshop($filteredRiders, $this->trip);

        return true;
    }

    private function firebaseRTDWorkshop(array $nearByRiders, Trip $trip)
    {
        $ridersToken = [];
        $ridersId = [];

        foreach ($nearByRiders as $key => $riderStatus) {
            //check for vehicle type
            if ($trip->vehicle_type == $riderStatus->driver->vehicleDetail->type) {
                if ($this->preference['genderBased'] == true && ($riderStatus->driver->gender == $trip->user->gender)) {
                    if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                        $ridersToken[] = $riderStatus->driver->device->device_token ?? '';
                        $ridersId[] = $riderStatus->driver->id;
                        $this->riderTripsFirebaseRTD($riderStatus->driver->id, $trip);
                    }
                } else {
                    //check if the rider has as already seen another ride request
                    if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                        $ridersToken[] = $riderStatus->driver->device->device_token ?? '';
                        $ridersId[] = $riderStatus->driver->id;
                        $this->riderTripsFirebaseRTD($riderStatus->driver->id, $trip);
                    }
                }
            }
        }

        $this->sendFCMNotification($ridersToken, $trip);
        $this->firebaseRTD($trip);
        $this->riderTempFirebaseRTD($ridersId, $trip->id);
    }

    private function sendFCMNotification(array $token, Trip $trip)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Requested by User',
                'user' => new UserResource($trip->user),
                'trip' => new TripResource($trip),
                'type' => 'request'
            ]
        );
        $notification->send();
    }

    private function riderTripsFirebaseRTD($driverId, Trip $trip)
    {
        $tripArray = [
            'user' => new UserResource($trip->user),
            'trip' => new TripResource($trip),
        ];

        $this->firebase->set($this->riderPath . $driverId, $tripArray);
    }

    private function firebaseRTD(Trip $trip)
    {
        $tripArray = [
            'user' => new UserResource($trip->user),
            'rider' => $trip->driver ? new DriverResource($trip->driver) : null,
            'trip' => new TripResource($trip),
        ];

        $this->firebase->set($this->path . $trip->id, $tripArray);
    }

    public function riderTempFirebaseRTD($driverIds, $tripId)
    {
        $this->firebase->set($this->riderTempPath . $tripId, $driverIds);
    }

    private function checkForFirebaseExistingDriverTrips($riderId)
    {
        try {
            $riderTemps = json_decode($this->firebase->get($this->riderPath . $riderId));

            if ($riderTemps) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            return true;
        }
    }
}
