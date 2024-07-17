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

class RideController extends Controller
{
    protected $firebase;
    protected $path = 'trips/';
    protected $riderPath = 'riderTrips/';
    protected $riderLocation = 'riderLocations/';
    protected $riderTempPath = 'riderTempTrips/';
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

        if (!$driver->isVerified()) {
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
        $bookedFor =  $trip->bookedFor;
        $bookedForNo = $trip->bookedForNo;
        if(!empty($bookedFor) && !empty($bookedForNo)){
            //send OTP sms to bookedForNo
            $message = "Hi, Your ride has been booked by ".ucfirst($trip->user->first_name);
            $message .= ". Kindly share this OTP:".$verificationCode." to the rider with Bike/Car ".$drive->vehicleDetail->plate_no.".
            Thank you";
            $message .= "Team gogo20";
            $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $bookedForNo, $message);
            $sms->sendMessage();
        }


        //Send notification to trip creator (user/customer)
        try {
            $this->firebaseRTD($trip);
            $this->firebaseRiderTempRTD($trip->id);
            $this->triggerNotification([$trip->user->device->device_token], $trip, $driver, $verificationCode);
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
                [$trip->user->device->device_token],
                [
                    'title' => 'Trip Completed',
                    'message' => 'Trip has been Completed',
                    'type' => 'trip-completed',
                ]
            );
            $notification->send();
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
            $this->firebaseTripDelRTD($trip->id);


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

        $notification = new PushNotification(
            [$trip->user->device->device_token],
            [
                'title' => 'Rider Arrived',
                'message' => 'Rider Arrived',
                'type' => 'arrived',
            ]
        );
        $notification->send();
        $this->firebaseRTD($trip);

        return successResponse('User has been notified.', 200, 200);
    }

    public function otherFirebaseNotification(User $user)
    {
        $notification = new PushNotification(
            [$user->device->device_token],
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

        $notification = new PushNotification(
            [$trip->user->device->device_token],
            [
                'title' => 'Trip Started',
                'message' => 'Trip has been Started.',
                'type' => 'trip-started',
            ]
        );
        $notification->send();
        $this->firebaseRTD($trip);

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
            $driver->status()->update(['status' => 'active']);
        } else if ($request->status == 'paused') {
            $trip->update(['status' => $request->status]);
        } else if ($request->status == 'resume') {
            $trip->update(['status' => 'started']);
        } else {
            $trip->update(['status' => 'ongoing']);

            $notification = new PushNotification(
                [$trip->user->device->device_token],
                [
                    'title' => 'Schedule Trip Started',
                    'message' => 'Be available at the pickup point.',
                    'type' => 'trip-started',
                ]
            );
            $notification->send();
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

        $this->firebase->set($this->path . $trip->id, $tripArray);
    }

    public function firebaseRiderTempRTD($tripId)
    {
        $temps = json_decode($this->firebase->get($this->riderTempPath . $tripId));

        foreach ($temps as $key => $riderId) {
            $this->firebase->delete($this->riderPath . $riderId);
        }
        $this->firebase->delete($this->riderTempPath . $tripId);
    }

    public function firebaseRiderDelRTD($tripId, $riderId)
    {
        $this->firebase->delete($this->riderPath . $riderId);
        $riderTemps = json_decode($this->firebase->get($this->riderTempPath . $tripId));
        if (!empty($riderTemps) && count($riderTemps) > 0) {
            foreach ($riderTemps as $key => $value) {
                if ($value == $riderId) {
                    $this->firebase->delete($this->riderTempPath . $tripId . '/' . $key);
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

        $this->firebase->set($this->riderLocation . $riderId, $riderInfo);
    }

    public function firebaseRiderLocationDelRTD($riderId)
    {
        $this->firebase->delete($this->riderLocation . $riderId);
    }

    public function firebaseTripDelRTD($tripId)
    {
        $this->firebase->delete($this->path . $tripId);
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

        if ($driver->settlement) {
            $payable = $trip->price < 100 ? 5 : 6;
            $driver->settlement->update(['payable_amount' => $driver->settlement->payable_amount + $payable]);
        } else {
            $payable = $trip->price < 100 ? 5 : 6;
            $driver->settlement()->create(['payable_amount' => $payable]);
        }
        // donation cod
        if ($request->donationTrust && $request->donation) {
            $this->donationProcess($request->donationTrust, $request->donation, $trip->user);
        }

        $this->sendInvoice($trip);
        return successResponse('Payment has been set to Received.', 200, 200);
    }

    public function ongoingTrip()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $trips = Trip::where('driver_id', $driver->id)->whereIn('status', ['pending', 'paused', 'ongoing', 'arrived', 'started'])->latest()->get();

        return TripResource::collection($trips)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function donationProcess($trust, $amount, $user)
    {
        $user->donations()->create(['trust' => $trust, 'donation' => $amount]);
    }

    public function sendInvoice($trip)
    {
        if ($trip->user->isVerified()) {
            try {
                Mail::to($trip->user)->send(new TripInvoice($trip));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}
