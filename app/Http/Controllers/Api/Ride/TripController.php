<?php

namespace App\Http\Controllers\Api\Ride;

use App\Trip;
use App\User;
use DateTime;
use App\Driver;
use App\PaymentLog;
use App\RentalTrip;
use App\DriverStatus;
use App\RentalPackage;
use App\OutstationTrip;
use Firebase\FirebaseLib;
use Illuminate\Http\Request;
use App\Helper\ResponseMessage;
use App\Custom\PushNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Custom\Payment\gogo\gogoWallet;
use Illuminate\Support\Facades\Validator;
use App\Custom\Payment\Esewa\EsewaResponse;
use App\Custom\Payment\Khalti\KhaltiResponse;
use App\Http\Resources\Api\Ride\TripResource;
use App\Http\Resources\Api\Ride\UserResource;
use App\Http\Resources\Api\Ride\DriverResource;
use App\Http\Resources\Api\Ride\RiderLocationResource;
use Arcanedev\LogViewer\Entities\Log;

class TripController extends Controller
{
    protected $firebase;
    protected $path = 'trips/';
    protected $riderPath = 'riderTrips/';
    protected $riderTempPath = 'riderTempTrips/';
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    public function __construct()
    {
        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
    }

    public function tripRequest(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'from' => 'required|string',
            'to' => 'required|string',
            'fromLat' => 'required|string|max:155',
            'fromLong' => 'required|string|max:155',
            'toLat' => 'required|string|max:155',
            'toLong' => 'required|string|max:155',
            'vehicle' => 'required|string|max:155',
            'distance' => 'nullable',
            'duration' => 'nullable',
            'bookedFor' => 'nullable',
            'bookedForNo' => 'nullable',
            'paymentMode' => 'required|string|max:155',
            'charge' => 'required|integer',
            'scheduleTrip' => 'nullable|in:true,false,1,0',
            'recurringInterval' => 'nullable|string|in:daily,weekly,monthly,onetime',
            'date' => 'nullable|string',
            'time' => 'bail|nullable|string', //required_if:scheduleTrip,true
            'preference' => 'bail|nullable'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        if (strtolower($request->from) == strtolower($request->to)) {
            return failureResponse("Starting point and Ending Point are same.", 418, 418);
        }

        $refNumber = "GGTRIPRef" . date("Ymd") . rand(11111, 99999) . $user->id;

        //scheduleTrip is true
        if ($request->scheduleTrip || $request->scheduleTrip == 1) {
            $trip = $user->trips()->create([
                'from' => $request->from,
                'to' => $request->to,
                'from_lat' => $request->fromLat,
                'from_long' => $request->fromLong,
                'to_lat' => $request->toLat,
                'to_long' => $request->toLong,
                'vehicle_type' => $request->vehicle,
                'payment_mode' => $request->paymentMode,
                'distance' => $request->distance,
                'duration' => $request->duration,
                'price' => $request->charge,
                'ref_number' => $refNumber
            ]);

            $schedule = $trip->schedule()->create([
                'user_id' => $trip->user_id,
                'recur' => $request->recurringInterval ?? 'onetime',
                'date' => $request->date ?? date('y-m-d'),
                'time' => $request->time
            ]);

            // $this->sendNotication($user, $trip);
            $tripAlgorithm = new TripRequestAlgorithm($user, $trip, $request->preference);
            $response = $tripAlgorithm->beginTransaction();

            if ($response) {
                return response()->json([
                    'tripId' => $trip->id,
                    'status'     => true,
                    'message'    => "Searching rider for your schedule trip....",
                    'statusCode' => 200,
                ], 200);
            }
            $trip->delete();
            return response()->json([
                'status'     => false,
                'message'    => "Something went wrong. Please try again later.",
                'statusCode' => 422,
            ], 422);
        }

        $trip = $user->trips()->create([
            'from' => $request->from,
            'to' => $request->to,
            'from_lat' => $request->fromLat,
            'from_long' => $request->fromLong,
            'to_lat' => $request->toLat,
            'to_long' => $request->toLong,
            'vehicle_type' => $request->vehicle,
            'payment_mode' => $request->paymentMode,
            'price' => $request->charge,
            'distance' => $request->distance,
            'duration' => $request->duration,
            'booked_for' => $request->bookedFor,
            'booked_for_no' => $request->bookedForNo,
            'ref_number' => $refNumber
        ]);


        //Work on Algorithm
        $tripAlgorithm = new TripRequestAlgorithm($user, $trip, $request->preference);
        $response = $tripAlgorithm->beginTransaction();

        if ($response) {
            return response()->json([
                'tripId' => $trip->id,
                'status'     => true,
                'message'    => "Searching for rider....",
                'statusCode' => 200,
            ], 200);
        }

        $trip->delete();
        return response()->json([
            'status'     => false,
            'message'    => "Something went wrong. Please try again later.",
            'statusCode' => 422,
        ], 422);
    }

    // Collect all riders nearby or favorite of users and send them notification
    public function sendNotication($user, $trip)
    {
        $nearByDistance = 5; // in km
        $availableNearByRiders = [];
        $ridersToken = [];
        $ridersId = [];
        // Collecting active riders
        $activeRiders = DriverStatus::where('status', 'active')->where(function ($query) {
            $query->where('interest', 'rider');
            $query->orWhere('interest', 'Rider');
        })->get(); //original
        // $activeRiders = DriverStatus::all();

        foreach ($activeRiders as $key => $rider) {
            $distance =  number_format((float) getDistance($trip->from_lat, $trip->from_long, $rider->lat, $rider->long), 2, '.', '');
            if ($distance <= $nearByDistance) {
                $availableNearByRiders[] = $rider;
            }
        }

        foreach ($availableNearByRiders as $key => $riderStatus) {
            if ($trip->vehicle_type == $riderStatus->driver->vehicleDetail->type) {
                if ($this->checkForFirebaseExistingDriverTrips($riderStatus->driver->id)) {
                    $ridersToken[] = $riderStatus->driver->device->device_token ?? '';
                    $ridersId[] = $riderStatus->driver->id;
                    $this->riderFirebaseRTD($riderStatus->driver->id, $user, $trip);
                }
            }
        }


        // Firebase Push Notification to availableNearByRiders
        $this->triggerNotification($ridersToken, $trip, $user);
        $this->firebaseRTD($user, $trip);
        $this->riderTempFirebaseRTD($ridersId, $trip->id);
    }

    public function rentalTripRequest(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'vehicle' => 'required|string|max:155',
            'paymentMode' => 'required|string|max:155',
            'from' => 'nullable|string',
            'fromLat' => 'nullable|string',
            'fromLong' => 'nullable|string',
            'date' => 'nullable|string',
            'time' => 'nullable|string',
            'rentalPackageId' => 'required|integer',
            'charge' => 'required|integer'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        if (!$package = RentalPackage::find($request->rentalPackageId)) {
            return failureResponse("Rental Package Not Found.", 404, 404);
        }

        if (!$request->date) {
            $request->date = date('Y-m-d');
        }

        if (!$request->time) {
            $request->time = date('H:i:s');
        }

        $startsAt  = $request->date . " " . $request->time;
        $endsAt  = date('Y-m-d H:i:s', strtotime($startsAt . ' +' . $package->duration . 'hour'));


        $trip = $user->rentalTrips()->create([
            'rental_package_id' => $request->rentalPackageId,
            'from' => $request->from,
            'from_lat' => $request->fromLat,
            'from_long' => $request->fromLong,
            'vehicle_type' => $request->vehicle,
            'payment_mode' => $request->paymentMode,
            'price' => $request->charge,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        return successResponse("Your Requested Package has been booked....", 200, 200);
    }

    public function rentalTripCancel(Request $request)
    {
        $rentalTrip = $request->cabRental_id;
        if (!$rentalTrip) {
            return failureResponse("cabRental_id field is required", 418, 418);
        }
        $rentalTrips = RentalTrip::findOrFail($rentalTrip);
        if ($rentalTrips->status == "pending") {
            $rentalTrips->status = "cancelled";
            $rentalTrips->save();
            return successResponse("Rental trip is cancelled successfully", 200, 200);
        } else {
            return failureResponse("Unable to perform your action", 418, 418);
        }
    }

    public function outstationTripCancel(Request $request)
    {
        $outstationTrip = $request->cabOutstation_id;
        if (!$outstationTrip) {
            return failureResponse("cabOutstation_id field is required", 418, 418);
        }
        $outstationTrips = OutstationTrip::findOrFail($outstationTrip);
        if ($outstationTrips->status == "pending") {
            $outstationTrips->status = "cancelled";
            $outstationTrips->save();
            return successResponse("Outstation trip is cancelled successfully", 200, 200);
        } else {
            return failureResponse("Unable to perform your action", 418, 418);
        }
    }

    public function outstationTripRequest(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'vehicle' => 'required|string|max:155',
            'paymentMode' => 'required|string|max:155',
            'from' => 'nullable|string',
            'fromLat' => 'nullable|string',
            'fromLong' => 'nullable|string',
            'to' => 'nullable|string',
            'toLat' => 'nullable|string',
            'toLong' => 'nullable|string',
            'date' => 'nullable|string',
            'time' => 'nullable|string',
            'package' => 'required|string',
            'charge' => 'required|integer'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }


        if (!$request->date) {
            $request->date = date('Y-m-d');
        }

        if (!$request->time) {
            $request->time = date('H:i:s');
        }

        $startsAt  = $request->date . " " . $request->time;


        $trip = $user->outstationTrips()->create([
            'package' => $request->package,
            'from' => $request->from,
            'from_lat' => $request->fromLat,
            'from_long' => $request->fromLong,
            'to' => $request->to,
            'to_lat' => $request->toLat,
            'to_long' => $request->toLong,
            'vehicle_type' => $request->vehicle,
            'payment_mode' => $request->paymentMode,
            'price' => $request->charge,
            'starts_at' => $startsAt,
        ]);

        return successResponse("Your Requested Package has been booked....", 200, 200);
    }

    public function triggerNotification(array $token, Trip $trip, User $user)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Requested by User',
                'user' => new UserResource($user),
                'trip' => new TripResource($trip),
                'type' => 'request'
            ]
        );
        $notification->send();
        // do firebase rtd
    }

    public function tripCancelled(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($trip->status !== "completed" && $trip->status !== "cancelled") {
            $trip->update(['status' => 'cancelled', 'cancelled_by' => 'User', 'logs' => $request->reason ?? '']);

            if ($trip->driver) {
                $this->otherFirebaseNotification($trip->driver);
                $trip->driver->status()->update(['status' => 'active']);
            }
            // $this->firebaseRTD($user, $trip);
            $this->firebaseTripDelRTD($trip->id);

            return successResponse('Trip has been cancelled.', 200, 200);
        }
        return failureResponse('Trip has already been set to completed or cancelled.', 418, 418);
    }

    public function tripStatus(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::where('user_id', $user->id)->Where('id', $request->tripId)->first()) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($request->status == 'dispute') {
            $trip->update(['status' => $request->status, 'dispute' => $request->reason, 'cancelled_by' => 'Customer']);
        } else if ($request->status == 'accident') {
            $trip->update(['status' => $request->status]);
            $trip->driver->status()->update(['status' => 'active']);
        } else if ($request->status == 'paused') {
            $trip->update(['status' => $request->status]);
        } else if ($request->status == 'resume') {
            $trip->update(['status' => 'started']);
        }

        $this->firebaseRTD($user, $trip);

        return response()->json([
            'data' =>
            [
                'trip' => new TripResource($trip),
                'rider' => $trip->driver ? new DriverResource($trip->driver) : ''
            ],
            'status'  => true, "message" => "",
            "statusCode" => 200
        ], 200);
    }

    public function riderLocation(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::where('user_id', $user->id)->Where('id', $request->tripId)->with('driver')->first()) {
            return failureResponse("Trip not found.", 404, 404);
        }

        return (new RiderLocationResource($trip->driver->status))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function otherFirebaseNotification(Driver $driver)
    {
        $notification = new PushNotification(
            [$driver->device->device_token],
            [
                'title' => 'Cancelled By User',
                'message' => 'Current Trip has been cancelled by User',
                'type' => 'cancelled',
            ]
        );
        $notification->send();
    }



    public function history()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return TripResource::collection($user->tripHistories)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function scheduleTrip()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $trips = Trip::where('user_id', $user->id)->Where('status', 'scheduled')->whereHas('schedule')->oldest()->get();
        return TripResource::collection($trips)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }


    public function tripPayment(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$trip = Trip::find($request->tripId)) {
            return failureResponse("Trip not found.", 404, 404);
        }

        if ($request->paymentMode != "cod") {

            if (!$request->token) {
                return failureResponse("Payment token is missing.", 422, 422);
            }

            try {
                DB::beginTransaction();
                $log = $this->paymentVerification($request->paymentMode, $request->totalAmount, $request->token);
                if ($log != false) {
                    if ($request->donationTrust && $request->donation) {
                        $this->donationProcess($request->donationTrust, $request->donation, $user);
                    }

                    $this->paymentSettlement($trip, $trip->driver);

                    $trip->update(['payment_mode' => $request->paymentMode]);

                    $this->firebaseRTD($user, $trip);

                    $this->paymentLog($request->totalAmount, $request, true, "ride hailing service", $trip->id, $log);
                    DB::commit();

                    return successResponse("Payment Received. Thank you.", 201, 201);
                } else {
                    $this->paymentLog($request->totalAmount, $request, false, "ride hailing service", $trip->id, null);
                    DB::commit();
                    return failureResponse(ResponseMessage::PAYMENTFAILED, 500, 500);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                //throw $th;
            }
        } else {
            return failureResponse("Payment Type is COD.", 422, 422);
        }
    }

    public function paymentVerification($mode, $total, $token = null)
    {
        switch ($mode) {
            case 'cod':
                return true;
                break;

            case 'gogoWallet':
                $wallet = new gogoWallet(auth()->guard('api')->user(), $total);
                if ($wallet->operation()) {
                    return $wallet;
                }
                return false;
                break;

            case 'esewa':
                $esewa = new EsewaResponse($token);
                if ($esewa->status() == 'approved') {
                    return $esewa;
                }
                return false;
                break;

            case 'khalti':
                $khalti = new KhaltiResponse($token, $total * 100);
                if ($khalti->status() == 'approved') {
                    return $khalti->transId();
                }
                return false;
                break;

            default:
                return false;
                break;
        }
    }

    public function donationProcess($trust, $amount, $user)
    {
        $user->donations()->create(['trust' => $trust, 'donation' => $amount]);
    }

    public function paymentSettlement($trip, $rider)
    {
        if ($rider->settlement) {
            $receivable = $trip->price < 100 ? 5 : 6;
            $rider->settlement->update(['receivable_amount' => ($rider->settlement->receivable_amount + ($trip->price - $receivable))]);
        } else {
            $receivable = $trip->price < 100 ? 5 : 6;
            $rider->settlement()->create(['receivable_amount' => ($trip->price - $receivable)]);
        }
    }

    public function ongoingTrip()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        return TripResource::collection($user->trips()->whereIn('status', ['pending', 'paused', 'ongoing', 'arrived', 'started'])->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function firebaseRTD(User $user, Trip $trip)
    {
        $tripArray = [
            'user' => new UserResource($user),
            'rider' => $trip->driver ? new DriverResource($trip->driver) : null,
            'trip' => new TripResource($trip),
        ];

        $this->firebase->set($this->path . $trip->id, $tripArray);
    }

    public function riderFirebaseRTD($driverId, User $user, Trip $trip)
    {
        $tripArray = [
            'user' => new UserResource($user),
            'trip' => new TripResource($trip),
        ];
        \Log::info($tripArray);
        $this->firebase->set($this->riderPath . $driverId, $tripArray);
    }

    public function riderTempFirebaseRTD($driverIds, $tripId)
    {
        $this->firebase->set($this->riderTempPath . $tripId, $driverIds);
    }

    public function firebaseTripDelRTD($tripId)
    {
        $this->firebase->delete($this->path . $tripId);

        //To Delete after cancellation
        try {
            $riderTempTrips = json_decode($this->firebase->get($this->riderTempPath . $tripId));
            foreach ($riderTempTrips as $key => $riderId) {
                $this->firebase->delete($this->riderPath . $riderId);
            }
            $this->firebase->delete($this->riderTempPath . $tripId);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function checkForFirebaseExistingDriverTrips($riderId)
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

    public function paymentLog($bill,  $request, $success, $action, $tripId, $tranId)
    {
        $transId = "";
        if ($request->paymentType == "esewa") {
            $transId = $request->token;
        } else if ($request->paymentType == "khalti") {
            $transId = $tranId;
        }

        $log = PaymentLog::create([
            'user_id' => auth()->guard('api')->id(),
            'task_id' => $tripId,
            'token' => $transId,
            'bill_amt' => $bill,
            'verified' => $success,
            'payment_mode' => $request->paymentMode,
            'ip' => request()->getClientIp(),
            'agent' => request()->header('User-Agent'),
            'action' => $action,
            'type' => 'trip'
        ]);
    }
}
