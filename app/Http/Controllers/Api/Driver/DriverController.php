<?php

namespace App\Http\Controllers\Api\Driver;

use App\Otp;
use App\Trip;
use App\Driver;
use App\Vendor;
use App\DriverDevice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\DriverVerifyEmail;
use App\Mail\VendorVerifyEmail;
use App\Services\DriverService;
use App\Services\VendorService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Driver\DriverResource;
use App\Http\Resources\Vendor\VendorResource;
use App\Http\Requests\Api\DriverRegisterRequest;
use App\Http\Requests\Api\VendorRegisterRequest;
use App\Http\Controllers\Vendor\CommonController;
use App\Http\Resources\Admin\RiderIncomeResource;

class DriverController extends CommonController
{

    /** @var DriverService */
    private $driverService;

    public function __construct(DriverService $driverService)
    {
        parent::__construct();
        $this->driverService = $driverService;
    }

    public function registerweb(DriverRegisterRequest $request)
    {
        // if (!$this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone)) {
        //     return failureResponse("otp doesnot match", 404, 404);
        // }
        $driver = Driver::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'dob' => $request->dob,
            'email' => $request->email,
            'country_code' => "+" . $request->countryCode,
            'phone' => $request->phone,
            'email_token' => str_random(10),
            'password' => bcrypt($request->password),
            'heard_from' => $request->heardFrom,
            'address' => $request->address,
            'lat' => $request->lat,
            'long' => $request->long,
            'interested_in' => $request->interestedIn,
            'rider' => $request->interestedIn == 'rider' ? 1 : 0,
            'ondemand' => $request->interestedIn == 'delivery' ? 1 : 0,
        ]);

        $driver->verifyPhone();
        $driver = $this->driverService->update($driver->id, $request->only('image'));
        $status = $driver->status()->create(['lat' => $request->lat, 'long' => $request->long]);
        if ($request->deviceToken && $request->deviceType) {
            $this->saveDevice($driver, $request);
        }
        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        try {
            Mail::to($driver)->send(new DriverVerifyEmail($driver->email_token));
        } catch (\Throwable $th) {
            //throw $th;
        }

        $token = auth()->guard('driver-api')->login($driver);
        $driver->token = $token;
        return (new DriverResource($driver))->additional(['status' => true, 'message' => "Registration successful.", 'statusCode' => 201], 201);
    }

    public function register(DriverRegisterRequest $request)
    {
        if (!$this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone)) {
            return failureResponse("otp doesnot match", 404, 404);
        }

        $driver = Driver::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'dob' => $request->dob,
            'email' => $request->email,
            'country_code' => "+" . $request->countryCode,
            'phone' => $request->phone,
            'email_token' => str_random(10),
            'password' => bcrypt($request->password),
            'heard_from' => $request->heardFrom,
            'address' => $request->address,
            'gender' => $request->gender,
            'lat' => $request->lat,
            'long' => $request->long,
            'interested_in' => $request->interestedIn,
            'ride' => $request->interestedIn == 'rider' ? 1 : 0,
            'ondemand' => $request->interestedIn == 'delivery' ? 1 : 0,
            'subscription' => $request->subscription ?? ''
        ]);

        $driver->verifyPhone();

        $driver = $this->driverService->update($driver->id, $request->only('image'));

        $status = $driver->status()->create(['lat' => $request->lat, 'long' => $request->long]);

        if ($request->deviceToken && $request->deviceType) {
            $this->saveDevice($driver, $request);
        }

        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        try {
            Mail::to($driver)->send(new DriverVerifyEmail($driver->email_token));
        } catch (\Throwable $th) {
            //throw $th;
        }

        $token = auth()->guard('driver-api')->login($driver);
        $driver->token = $token;
        return (new DriverResource($driver))->additional(['status' => true, 'message' => "Registration successful.", 'statusCode' => 201], 201);
    }

    public function login(LoginRequest $request)
    {
        if ($request->email) {
            $token = auth()->guard('driver-api')->attempt([
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'email_verified' => 1
            ]);
        } else {
            $token = auth()->guard('driver-api')->attempt([
                'country_code' => '+' . $request->input('countryCode'),
                'phone' => $request->input('phone'),
                'password' => $request->input('password'),
                'phone_verified' => 1,
            ]);
        }

        if ($token) {
            $driver = auth()->guard('driver-api')->user();

            if ($driver->isBlocked()) {
                return failureResponse("Your Account has been blocked. Please contact to administrator.", 403, 403);
            }

            if ($driver->blacklisted >= 3) {
                return failureResponse("Your Account has been blacklisted for " . $driver->blacklisted . " times. Please contact to administrator.", 403, 403);
            }


            if ($request->deviceToken && $request->deviceType) {
                $this->saveDevice($driver, $request);
            }

            $driver['token'] = $token;
            return (new DriverResource($driver))->additional(['status' => true, 'message' => "Login successful.", 'statusCode' => 200], 200);
        }

        return failureResponse("These credentials do not match our records.", 401, 401);

        throw new \Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);
    }


    /**
     * Log user out
     *
     */
    public function logout()
    {
        auth()->guard('driver-api')->logout();

        return successResponse('Successfully logged out.');
    }

    /**
     * Refresh login access token of user
     *
     * @return UserResource
     */
    public function refreshAccessToken()
    {
        return successResponse('Token refreshed successfully.')
            ->withHeaders([
                'X-Access-Token'     => auth()->guard('driver-api')->refresh(),
                'X-Token-Type'       => 'bearer',
                'X-Token-Expires-In' => auth()->guard('driver-api')->factory()->getTTL() * 60,
            ]);
    }

    /**
     * Add token to blacklist
     *
     * @return \Exception
     */
    public function blacklistAccessToken()
    {
        auth()->guard('driver-api')->invalidate();

        return successResponse('Token added to blacklist');
    }

    public function verifyEmail(Request $request)
    {
        $user = auth()->guard('driver-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }


        $validator = Validator::make($request->all(), [
            'token' => 'required|string|min:10|max:10',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        try {
            $user->where('email_token', $request->token)->firstOrFail()->verifyEmail();
            return successResponse("E-mail is verified now.");
        } catch (\Throwable $th) {
            return failureResponse("Token doesn't match.", 404, 404);
        }
    }

    public function verifyMyOtp($otp, $phone)
    {
        try {
            $isExist = Otp::where('otp', $otp)->Where('phone', $phone)->firstOrFail();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function saveDevice($driver, $request)
    {
        if (!($driver->device) && $request->deviceType && $request->deviceToken) {
            if (DriverDevice::where('device_token', $request->deviceToken)->count() == 0) {
                $driver->device()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
            }
        } elseif (($driver->device) && $request->deviceType && $request->deviceToken) {
            $driverDevice = $driver->device;
            if ($driverDevice['device_token'] != $request->deviceToken) {
                $driver->device()->update(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
            }
        }
    }

    public function verify($token)
    {
        Driver::where('email_token', $token)->firstOrFail()->verifyEmail();

        return redirect('/')->with('success_message', 'Your email is verified.');
    }

    public function stat(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return response()->json([
            'data' => [
                'verified' => $driver->isVerified(),
                'rating' => $driver->averageRating(),
                'isBlocked' => $driver->is_blocked == 1,
                'blackListed' => $driver->blacklisted ?? 0,
                'totalCompletedTrips' => $driver->completedTrips->count(),
                'completedTrips' => $driver->completedTrips()->whereDate('completed_at', date('Y-m-d'))->count(),
                'completedDeliveries' => $driver->completedDeliveries()->whereDate('delivered_at', date('Y-m-d'))->count(),
                'cancelledTrips' => $driver->cancelledTrips->count(),
                'payableAmount' => round($driver->settlement ? $driver->settlement->payable_amount : 0),
                'receivableAmount' => round($driver->settlement ? $driver->settlement->receivable_amount : 0),
                'lifeTimeEarning' => round($driver->completedTrips->sum('price')),
                'totalEarning' => round($driver->completedTrips()->whereDate('completed_at', date('Y-m-d'))->sum('price')),
                'rideSoFar' => $driver->completedTrips->sum('distance1'),
                'isDocumentSubmitted' => $driver->vehicleDetail ? true : false
            ],
            'status' => true,
            'statusCode' => 200
        ], 200);
    }

    public function leaderBoard()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $trips = Trip::select(DB::raw('count(*) as total, driver_id, sum(price) as price'))
            ->groupBy('driver_id')
            ->where('status', 'completed')
            ->WhereDate('created_at', date('Y-m-d'))
            ->with('driver')
            ->orderBy('price', 'DESC')
            ->limit(10)
            ->get();

        return RiderIncomeResource::collection($trips)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
