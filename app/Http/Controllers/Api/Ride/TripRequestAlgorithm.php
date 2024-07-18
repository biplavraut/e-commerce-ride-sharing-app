<?php

namespace App\Http\Controllers\Api\Ride;

use App\Trip;
use App\User;
use App\DriverStatus;
use App\TripRequestLog;
use Firebase\FirebaseLib;
use App\Custom\PushNotification;
use App\Http\Resources\Api\Ride\TripResource;
use App\Http\Resources\Api\Ride\UserResource;
use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Api\Ride\DriverResource;
use App\Vehicle;

class TripRequestAlgorithm extends CommonController
{
    protected $firebase;
    // protected $path = 'test/trips/';
    // protected $riderPath = 'test/riderTrips/';
    // protected $riderTempPath = 'test/riderTempTrips/';
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    protected $user;
    protected $trip;
    protected $preference;

    protected $recall = false;
    protected $conf = [];


    public function __construct(User $user, Trip $trip, $preference, $recall = false)
    {
        parent::__construct();
        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
        $this->user = $user;
        $this->trip = $trip;
        $this->preference = $preference;
        $this->recall = $recall;
    }

    public function beginTransaction()
    {
        $nearByDistance = $this->conf['nearby_radius']; // in km
        $availableNearByRiderStatuses = [];
        $filteredRiderStatuses = [];

        //lastly added
        $this->firebaseRTD($this->trip);


        // Collecting active riders
        $activeRiderStatuses = DriverStatus::where('status', 'active')->where(function ($query) {
            $query->where('interest', 'rider');
            $query->orWhere('interest', 'Rider');
        })->get();


        foreach ($activeRiderStatuses as $key => $status) {

            //getting air distance of status and pickup point
            $distance =  number_format((float) getDistance($this->trip->from_lat, $this->trip->from_long, $status->lat, $status->long), 2, '.', '');

            if ($distance <= $nearByDistance) {
                $availableNearByRiderStatuses[] = $status;
            }
        }


        //Add Preference
        if ($this->preference['childSeat'] == true ||  $this->preference['handiCapSupport'] == true ||  $this->preference['noSmoking'] == true) {
            foreach ($availableNearByRiderStatuses as $key => $nearByRiderStatus) {

                try {
                    $pref = $nearByRiderStatus->driver->preference()->where(function ($query) {
                        if ($this->preference['noSmoking']) {
                            $query->where('smoking', !$this->preference['noSmoking']);
                        }

                        if ($this->preference['childSeat']) {
                            $query->where('child_seat', $this->preference['childSeat']);
                        }

                        if ($this->preference['handiCapSupport']) {
                            $query->where('handicap_support', $this->preference['handiCapSupport']);
                        }
                    })->first();

                    if ($pref) {
                        $filteredRiderStatuses[] = $nearByRiderStatus;
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else {

            $filteredRiderStatuses = $availableNearByRiderStatuses;
        }

        $this->firebaseRTDWorkshop($filteredRiderStatuses, $this->trip);

        return true;
    }

    private function firebaseRTDWorkshop(array $nearByRiderStatuses, Trip $trip)
    {
        $ridersToken = [];
        $ridersId = [];

        $vehicle = Vehicle::where('type', 'LIKE', '%' . strtolower($trip->vehicle_type) . '%')->first();


        foreach ($nearByRiderStatuses as $key => $riderStatus) {
            //check for vehicle type
            if (!$this->recall) {
                if ($riderStatus->driver->verified == true && $riderStatus->driver->is_blocked == false) {
                    try {
                        if ($vehicle->id == $riderStatus->driver->myVehicle()->id) {
                            if ($this->preference['genderBased'] == true) {

                                if (($riderStatus->driver->gender == $trip->user->gender)) {
                                    if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                                        try {
                                            $ridersToken =  array_merge($ridersToken, $riderStatus->driver->devices->pluck('device_token')->toArray());
                                        } catch (\Throwable $th) {
                                            //throw $th;
                                        }
                                        $ridersId[] = $riderStatus->driver->id;
                                        $this->riderTripsFirebaseRTD($riderStatus->driver->id, $trip);
                                    }
                                }
                            } else {
                                // dd($riderStatus->driver->id);
                                //check if the rider has as already seen another ride request
                                if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                                    //
                                    try {
                                        $ridersToken =  array_merge($ridersToken, $riderStatus->driver->devices->pluck('device_token')->toArray());
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    $ridersId[] = $riderStatus->driver->id;
                                    $this->riderTripsFirebaseRTD($riderStatus->driver->id, $trip);
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            } else {
                $log = $trip->log;
                $exclude = in_array($riderStatus->driver_id, $log->rider_list); //array_search($riderStatus->driver_id, $log->rider_list, true);


                if ($riderStatus->driver->verified == true && $riderStatus->driver->is_blocked == false && !$exclude) {

                    try {
                        if ($vehicle->id == $riderStatus->driver->myVehicle()->id) {

                            if ($this->preference['genderBased'] == true && ($riderStatus->driver->gender == $trip->user->gender)) {

                                if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                                    try {
                                        $ridersToken =  array_merge($ridersToken, $riderStatus->driver->devices->pluck('device_token')->toArray());
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    $ridersId[] = $riderStatus->driver->id;
                                    $this->riderTripsFirebaseRTD($riderStatus->driver->id, $trip);
                                }
                            } else {
                                //check if the rider has as already seen another ride request
                                if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                                    try {
                                        $ridersToken =  array_merge($ridersToken, $riderStatus->driver->devices->pluck('device_token')->toArray());
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                    $ridersId[] = $riderStatus->driver->id;
                                    $this->riderTripsFirebaseRTD($riderStatus->driver->id, $trip);
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
        }

        //Write to Trip Request Log
        $this->writeToLog($ridersId, $trip->id);

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

        $this->firebase->set(env('RIDER_TRIP_PATH', 'riderTrips/') . $driverId, $tripArray);
    }

    private function firebaseRTD(Trip $trip)
    {
        $tripArray = [
            'user' => new UserResource($trip->user),
            'rider' => $trip->driver ? new DriverResource($trip->driver) : null,
            'trip' => new TripResource($trip),
        ];

        $this->firebase->set(env('TRIP_PATH', 'trips/') . $trip->id, $tripArray);
    }

    public function riderTempFirebaseRTD($driverIds, $tripId)
    {
        $this->firebase->set(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId, $driverIds);
    }

    private function checkForFirebaseExistingDriverTrips($riderId)
    {
        try {
            $riderTemps = json_decode($this->firebase->get(env('RIDER_TRIP_PATH', 'riderTrips/') . $riderId));

            if ($riderTemps) {
                return false;
            }
            return true;
        } catch (\Throwable $th) {
            return true;
        }
    }


    private function writeToLog($riders, $tripId)
    {
        if (!$this->recall) {
            $log = TripRequestLog::create([
                'trip_id' => $tripId,
                'rider_list' => json_encode($riders)
            ]);
        } else {

            if ($this->trip->log) {
                $log = $this->trip->log;

                $list = $log->rider_list;

                foreach ($list as $key => $oldRider) {
                    $this->firebase->delete(env('RIDER_TRIP_PATH', 'riderTrips/') . $oldRider);
                }


                foreach ($riders as $newRider) {
                    array_push($list, $newRider);
                }

                $log->update(['rider_list' => json_encode($list)]);
            } else {
                $log = TripRequestLog::create([
                    'trip_id' => $tripId,
                    'rider_list' => json_encode($riders)
                ]);
            }
        }
    }
}
