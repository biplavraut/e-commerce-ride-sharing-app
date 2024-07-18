<?php

namespace App\Http\Controllers\Api\Driver\Ride;

use App\Trip;
use App\User;
use App\Driver;
use Firebase\FirebaseLib;
use Illuminate\Http\Request;
use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Ride\TripResource;
use App\Http\Resources\Api\Ride\UserResource;
use App\Http\Resources\Api\Ride\DriverResource;
use App\Mail\TripInvoice;
use App\Custom\Sms\AakashSms;
use App\Custom\Sms\Sparrow;
use App\DefaultConf;
use App\OrderOfferConf;
use App\RideOfferConf;

class RideController extends Controller
{
    protected $firebase;
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    public function __construct()
    {
        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
    }

    public function updateStatus(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }


        $validator = Validator::make($request->all(), [
            'lat' => 'required|string',
            'long' => 'required|string',
            'status' => 'nullable|in:true,false,0,1',
            'bearing' => 'nullable'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        if ($driver->status) {
            $status = $driver->status()->update(
                [
                    'lat' => $request->lat,
                    'long' => $request->long,
                    'interest' => $driver->interested_in ?? 'rider',
                    'updated_at' => now(),
                    'bearing' => $request->bearing ?? null,
                    'status' => $driver->status->status != 'ongoing' ? 'active' : 'ongoing'
                ]
            );

            if ($driver->status->status == "ongoing") {
                $this->firebaseRiderLocationRTD($driver->id, $driver->status);
            }
            return successResponse('Location Status Updated.', 200, 200);
        }

        $status = $driver->status()->create(
            [
                'lat' => $request->lat,
                'long' => $request->long,
                'bearing' => $request->bearing ?? null,
                'interest' => $driver->interested_in ?? 'rider',
                'updated_at' => now()
            ]
        );
        // $this->firebaseRiderLocationRTD($driver->id, $status);
        return successResponse('Location Status Created.', 200, 200);
    }

    public function acceptTrip(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::with('user')->find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($driver->isBlocked()) {
            $this->firebaseRiderDelRTD($trip->id, $driver->id);
            return failureResponse("Your Account has been blocked. Please contact to administrator.", 403, 403);
        }

        if ($driver->blacklisted >= 3) {
            $this->firebaseRiderDelRTD($trip->id, $driver->id);
            return failureResponse("Your Account has been blacklisted for " . $driver->blacklisted . " times. Please contact to administrator.", 403, 403);
        }

        if ($trip->driver_id) {
            return failureResponse("Trip has already been assigned.", 418, 418);
        }

        if (!$driver->isVerified() && !$driver->isPartiallyVerified()) {
            return failureResponse("You're not verified rider. Please verify your vehilce detail to accept trips.", 404, 404);
        }

        $verificationCode = randomNumericString(4);

        $message = "Trip has been assigned for you. Please be available at the Customer location ASAP.";

        if ($trip->schedule) {
            $trip->update(['driver_id' => $driver->id, 'status' => 'scheduled', 'otp' => $verificationCode]);
            $message = "Trip has been assigned for you. Please be available at the Customer location in scheduled datetime.";
        } else {
            $trip->update(['driver_id' => $driver->id, 'status' => 'ongoing', 'otp' => $verificationCode]);
            $driver->status()->update(['status' => 'ongoing']);
        }

        ///Sent sms if the user is booking this trip for someone else
        if ($trip->booked_for && $trip->booked_for_no) {
            //send OTP sms to bookedForNo
            $message = "Hi, Your ride has been booked by " . ucfirst($trip->user->first_name);
            $message .= ". Kindly share this OTP : " . $verificationCode . " to the rider with " . ucfirst($driver->vehicleDetail->type)  . ' No. ' . $driver->vehicleDetail->plate_no . ".
            Thank you";
            $message .= "\nTeam gogo20";
            $sms = new Sparrow($trip->booked_for_no, $message);
            $sms->sendMessage();
        }


        //Send notification to trip creator (user/customer)
        try {
            $this->firebaseRTD($trip);
            $this->firebaseRiderTempRTD($trip->id);
            $this->triggerNotification($trip->user->devices->pluck('device_token')->toArray(), $trip, $driver, $verificationCode);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'otp' => $verificationCode,
            'statusCode' => 200
        ], 200);
    }

    public function tripCompleted(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($request->to && $request->toLat && $request->toLong && $request->charge) {
            $trip->update(['to' => $request->to, 'to_lat' => $request->toLat, 'to_long' => $request->toLong, 'price' => $request->charge]);
        }

        try {
            $notification = new PushNotification(
                $trip->user->devices->pluck('device_token')->toArray(),
                [
                    'title' => 'Trip Completed',
                    'message' => 'Trip has been Completed',
                    'type' => 'trip-completed',
                ]
            );
            $notification->send();

            $trip->user->myNotifications()->create(['title' => 'Trip Completed', 'message' => 'Your ongoing trip has been marked as completed.', 'type' => 'trip', 'task' => $trip->tripId()]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        $trip->update(['status' => 'completed', 'completed_at' => now()]);
        $driver->status()->update(['status' => 'active']);
        $this->firebaseRiderLocationDelRTD($driver->id);
        $this->firebaseRTD($trip);
        // $this->firebaseTripDelRTD($trip->id);

        return successResponse('Trip has been set to completed.', 200, 200);
    }

    public function tripCancelled(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($trip->status !== "completed") {
            $trip->update(['status' => 'cancelled', 'cancelled_by' => 'Rider', 'logs' => $request->reason]);

            $this->otherFirebaseNotification($trip->user);
            $this->firebaseRTD($trip);


            $driver->status()->update(['status' => 'active']);
            $this->firebaseRiderLocationDelRTD($driver->id);
            // $this->firebaseTripDelRTD($trip->id);

            $trip->user->myNotifications()->create(['title' => 'Trip cancelled', 'message' => 'Your current trip has been cancelled by rider.', 'type' => 'trip', 'task' => $trip->tripId()]);


            return successResponse('Trip has been set to cancelled by you.', 200, 200);
        }
        return failureResponse('Trip has already been set to completed.', 418, 418);
    }

    public function triggerNotification(array $token, $trip, Driver $driver, $otp)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Accepted by Rider',
                'trip' => new TripResource($trip),
                'rider' => new DriverResource($driver),
                'type' => 'accept',
                'otp' => $otp
            ]
        );
        $notification->send();
        $trip->user->myNotifications()->create(['title' => 'Trip Request Accepted', 'message' => 'Your ongoing trip request has been accepted by rider.', 'type' => 'trip', 'task' => $trip->tripId()]);
    }

    public function arrivedToPickupPoint(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($trip->status == "ongoing") {
            $trip->update(['status' => 'arrived']);
        }

        // $notification = new PushNotification(
        //     $trip->user->devices->pluck('device_token')->toArray(),
        //     [
        //         'title' => 'Rider Arrived',
        //         'message' => 'Rider Arrived',
        //         'type' => 'arrived',
        //     ]
        // );
        // $notification->send();
        $this->firebaseRTD($trip);

        $trip->user->myNotifications()->create(['title' => 'Rider Arrived', 'message' => 'Rider just arrived to pickup point.', 'type' => 'trip', 'task' => $trip->tripId()]);

        return successResponse('User has been notified.', 200, 200);
    }

    public function otherFirebaseNotification(User $user)
    {
        $notification = new PushNotification(
            $user->devices->pluck('device_token')->toArray(),
            [
                'title' => 'Cancelled By Rider',
                'message' => 'Current Trip has been cancelled by Rider',
                'type' => 'cancelled',
            ]
        );
        $notification->send();
    }

    public function tripStart(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($trip->status == "arrived") {
            $trip->update(['status' => 'started']);
        }

        // $notification = new PushNotification(
        //     $trip->user->devices->pluck('device_token')->toArray(),
        //     [
        //         'title' => 'Trip Started',
        //         'message' => 'Trip has been Started.',
        //         'type' => 'trip-started',
        //     ]
        // );
        // $notification->send();
        $this->firebaseRTD($trip);

        $trip->user->myNotifications()->create(['title' => 'Trip Started', 'message' => 'Your trip has been started.', 'type' => 'trip', 'task' => $trip->tripId()]);


        return successResponse('User has been notified.', 200, 200);
    }

    public function tripStatus(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::where('driver_id', $driver->id)->Where('id', $request->tripId)->first()) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($request->status == 'dispute') {
            $trip->update(['status' => $request->status, 'dispute' => $request->reason, 'cancelled_by' => 'Rider']);
        } else if ($request->status == 'accident') {
            $trip->update(['status' => $request->status]);
            $driver->status()->update(['status' => 'ongoing']);
        } else if ($request->status == 'paused') {
            $trip->update(['status' => $request->status]);
        } else if ($request->status == 'resume') {
            $trip->update(['status' => 'started']);
        } else {
            $trip->update(['status' => 'ongoing']);
            $driver->status()->update(['status' => 'ongoing']);

            $notification = new PushNotification(
                $trip->user->devices->pluck('device_token')->toArray(),
                [
                    'title' => 'Schedule Trip Started',
                    'message' => 'Be available at the pickup point.',
                    'type' => 'trip-started',
                ]
            );
            $notification->send();

            $trip->user->myNotifications()->create(['title' => 'Schedule Trip Started', 'message' => 'Be available at the pickup point.', 'type' => 'trip', 'task' => $trip->tripId()]);
        }

        $this->firebaseRTD($trip);


        return response()->json([
            'data' =>
            [
                'trip' => new TripResource($trip),
                'user' => new UserResource($trip->user)
            ],
            'status'  => true,
            "message" => "",
            "statusCode" => 200
        ], 200);
    }

    public function reject(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::where('id', $request->tripId)->first()) {
            return failureResponse("Trip not found.", 404, 404);
        }
        $this->firebaseRiderDelRTD($trip->id, $driver->id);

        return successResponse('Trip rejected.', 200, 200);
    }

    public function firebaseRTD($trip)
    {
        $tripArray = [
            'user' => new UserResource($trip->user),
            'rider' => $trip->driver ? new DriverResource($trip->driver) : null,
            'trip' => new TripResource($trip),
        ];

        $this->firebase->set(env('TRIP_PATH', 'trips/') . $trip->id, $tripArray);
    }

    public function firebaseRiderTempRTD($tripId)
    {
        $temps = json_decode($this->firebase->get(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId));

        foreach ($temps as $key => $riderId) {
            $this->firebase->delete(env('RIDER_TRIP_PATH', 'riderTrips/') . $riderId);
        }
        $this->firebase->delete(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId);
    }

    public function firebaseRiderDelRTD($tripId, $riderId)
    {
        $this->firebase->delete(env('RIDER_TRIP_PATH', 'riderTrips/') . $riderId);
        $riderTemps = json_decode($this->firebase->get(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId));
        if (!empty($riderTemps) && count($riderTemps) > 0) {
            foreach ($riderTemps as $key => $value) {
                if ($value == $riderId) {
                    $this->firebase->delete(env('RIDER_TEMP_PATH', 'riderTempTrips/') . $tripId . '/' . $key);
                }
            }
        }
    }

    public function firebaseRiderLocationRTD($riderId, $status)
    {
        $riderInfo = [
            'lat' => $status->lat,
            'long' => $status->long,
            'bearing' => $status->bearing ?? null,
            'status' => $status->status,
        ];

        // $this->firebase->set($this->riderLocation . $riderId, $riderInfo);
    }

    public function firebaseRiderLocationDelRTD($riderId)
    {
        // $this->firebase->delete($this->riderLocation . $riderId);
    }

    public function firebaseTripDelRTD($tripId)
    {
        $this->firebase->delete(env('TRIP_PATH', 'trips/') . $tripId);
    }

    public function history()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        return TripResource::collection($driver->tripHistories)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function scheduleTrip()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $trips = Trip::where('driver_id', $driver->id)->Where('status', 'scheduled')->whereHas('schedule')->oldest()->get();
        return TripResource::collection($trips)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function tripPayment(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::where('driver_id', $driver->id)->Where('id', $request->tripId)->first()) {
            return failureResponse("Trip not found.", 404, 404);
        }

        //newly added
        $trip->update(['payment_mode' => $request->paymentMode ?? $trip->payment_mode, 'done' => 1]);


        if ($driver->packages()->count() > 0) {
            $currentPackage = $driver->currentPackage();
            if ($currentPackage->duration == "per-ride" && $currentPackage->type == "amount") {

                if ($driver->vehicles()->count() > 0) {
                    $vehicle = $driver->myVehicle();
                    $value = $vehicle->name == "2 Wheeler" ? $currentPackage->two_wheel_value : $currentPackage->four_wheel_value;
                } else {
                    $value = $currentPackage->two_wheel_value;
                }
                $driver->settlement->update(['payable_amount' => ($driver->settlement->payable_amount + $value)]);
            } else if ($currentPackage->duration == "per-ride" && $currentPackage->type == "percent") {
                if ($driver->vehicles()->count() > 0) {
                    $vehicle = $driver->myVehicle();
                    $value = $vehicle->name == "2 Wheeler" ? $currentPackage->two_wheel_value : $currentPackage->four_wheel_value;
                } else {
                    $value = $currentPackage->two_wheel_value;
                }
                $toPay = ($trip->price * $value) / 100;
                $driver->settlement->update(['payable_amount' => ($driver->settlement->payable_amount + $toPay)]);
            }
        } else {
            if ($driver->settlement) {
                $driver->settlement->update(['payable_amount' => ($driver->settlement->payable_amount + 5)]);
            } else {
                $driver->settlement->create(['payable_amount' => 5]);
            }
        }
        $user = $trip->user_id;
        if (User::find($user)) {
            $offerActive = false;
            $offerDiscount = 0;
            $activeRideOffer = RideOfferConf::where('enabled', 1)->where('from', '<=', date('Y-m-d H:i:s'))->where('to', '>', date('Y-m-d H:i:s'))->first();
            if ($activeRideOffer) {
                // Check if user is eligible for offer
                $countTrips = Trip::where('user_id', $user)
                    ->where('status', 'completed')
                    ->where('created_at', '>=', $activeRideOffer->from)
                    ->where('created_at', '<=', $activeRideOffer->to)->count();
                $remainingOrders = $activeRideOffer->no_of_rides - $countTrips;

                if ($remainingOrders > 0) {
                    $offerActive = true;
                    $offerDiscount = $activeRideOffer->discount;
                } else {
                    $offerActive = false;
                    $offerDiscount = $activeRideOffer->discount;
                }
            }
            if ($offerActive) {
                $offerDiscountPrice = ($trip->price * $offerDiscount) / 100;
                $trip->user->update(['reward_point' => $trip->user->reward_point + round($offerDiscountPrice)]);

                // if ($trip->user->gogoWallet) {
                //     $trip->user->gogoWallet()->update(['amount' => $trip->user->gogoWallet->amount + $offerDiscountPrice]);
                // } else {
                //     $trip->user->gogoWallet()->create(['amount' => $offerDiscountPrice]);
                // }

                $trip->user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $offerDiscountPrice, 'from' => 'gogoCab Cashback']);
            }
        }

        $conf = DefaultConf::first();
        if ($conf && $conf->purchase_total != 0  && $conf->purchase_total != 0 && $conf->cashback_percent != 0) {
            if ($trip->price >= $conf->purchase_total) {
                $trip->user->update(['reward_point' => $trip->user->reward_point + round(($trip->price * $conf->cashback_percent) / 100)]);
                // if ($trip->user->gogoWallet) {
                //     $trip->user->gogoWallet()->update(['amount' => $trip->user->gogoWallet->amount + round(($trip->price * $conf->cashback_percent) / 100)]);
                // } else {
                //     $trip->user->gogoWallet()->create(['amount' => round(($trip->price * $conf->cashback_percent) / 100)]);
                // }
                $trip->user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => round(($trip->price * $conf->cashback_percent) / 100), 'from' => 'Trip Cashback']);
            }
        }


        $driver->status()->update(['status' => 'active']);


        $this->sendInvoice($trip);
        $this->firebaseTripDelRTD($trip->id);
        return successResponse('Payment has been set to Received.', 200, 200);
    }

    public function ongoingTrip()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $trips = Trip::where('driver_id', $driver->id)
            ->where('status', '!=', 'scheduled')
            ->where('done', 0)
            ->latest()->get();

        return TripResource::collection($trips)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function donationProcess($trust, $amount, $user)
    {
        $user->donations()->create(['trust' => $trust, 'donation' => $amount]);
    }

    public function sendInvoice($trip)
    {
        if ($trip->user->email != '') {
            // try {
            Mail::to($trip->user)->send(new TripInvoice($trip));
            // } catch (\Throwable $th) {
            //     throw $th;
            // }
        }
    }
}
