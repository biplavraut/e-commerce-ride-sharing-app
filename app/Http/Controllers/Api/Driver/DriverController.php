<?php

namespace App\Http\Controllers\Api\Driver;

use App\Otp;
use App\Trip;
use App\Driver;
use App\Vendor;
use App\Vehicle;
use App\Voucher;
use App\DriverDevice;
use App\RiderReferral;
use Illuminate\Support\Str;
use App\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\DriverVerifyEmail;
use App\Mail\VendorVerifyEmail;
use App\Services\DriverService;
use App\Services\VendorService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Custom\Helper\EmailValidator;
use App\DefaultConf;
use App\Services\DriverVehicleService;
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


    /**
     * @var DriverVehicleService
     */
    private $driverVehicleService;

    public function __construct(DriverService $driverService, DriverVehicleService $driverVehicleService)
    {
        parent::__construct();
        $this->driverService = $driverService;
        $this->driverVehicleService = $driverVehicleService;
    }

    public function registerweb(DriverRegisterRequest $request)
    {
        if (!$this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone)) {
            return failureResponse("otp doesnot match", 404, 404);
        }

        if ($request->email) {
            $response = new EmailValidator($request->email);

            if (!$response->validate()) {
                return response()->json(['message' => 'The given data was invalid', 'errors' => ['email' => ['Email is invalid.']]], 422);
            }
        }

        try {
            $lastRecordId = Driver::orderBy("id", 'desc')->limit(1)->first()->id;
        } catch (\Throwable $th) {
            $lastRecordId = 0;
        }
        $refer = "gogor" . $lastRecordId;
        $code = strtoupper($refer . randomAlphaNumericString(5));

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
            'interested_in' =>  $request->interestedIn ?? 'rider',
            'rider' => $request->interestedIn == 'rider' ? 1 : 0,
            'ondemand' => $request->interestedIn == 'delivery' ? 1 : 0,
            'subscription' => $request->subscription ?? '',
            'refer_code' => substr($code, 0, 10),
            'from' => 'web'
        ]);

        $driver->verifyPhone();

        $status = $driver->status()->create(['lat' => $request->lat, 'long' => $request->long, 'interest' => 'rider']);
        if ($request->deviceToken && $request->deviceType) {
            $this->saveDevice($driver, $request);
        }
        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        try {
            if ($request->email) {
                // Mail::to($driver)->send(new DriverVerifyEmail($driver->email_token));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($request->subscriptionPackageId) {
            try {
                $package = SubscriptionPackage::find($request->subscriptionPackageId);
                $driver->packages()->attach($package);
                $driver->settlement()->create();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        //Validating code
        if (Str::contains($request->referCode, '#$')) {
            $voucher = Voucher::where('code', strtoupper($request->referCode))->first();
            if ($voucher) {
                if ($voucher->user->gogoWallet) {
                    $voucher->user->gogoWallet()->update(['amount' => $voucher->user->gogoWallet->amount + $voucher->amount]);
                } else {
                    $voucher->user->gogoWallet()->create(['amount' => $voucher->amount]);
                }
                $voucher->user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $voucher->amount, 'from' => 'Voucher',]);
            }
        } else {
            $this->isValidReferCode($request->referCode, $driver);
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

        if ($request->email) {
            $response = new EmailValidator($request->email);

            if (!$response->validate()) {
                return failureResponse("Invalid email.", 422, 422);
            }
        }

        try {
            $lastRecordId = Driver::orderBy("id", 'desc')->limit(1)->first()->id;
        } catch (\Throwable $th) {
            $lastRecordId = 0;
        }
        $refer = "gogor" . $lastRecordId;
        $code = strtoupper($refer . randomAlphaNumericString(5));

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
            'interested_in' => $request->interestedIn ?? 'rider',
            'ride' => $request->interestedIn == 'rider' ? 1 : 0,
            'ondemand' => $request->interestedIn == 'delivery' ? 1 : 0,
            'subscription' => $request->subscription ?? '',
            'refer_code' => substr($code, 0, 10)
        ]);

        $driver->verifyPhone();

        $driver = $this->driverService->update($driver->id, $request->only('image'));

        $status = $driver->status()->create(['lat' => $request->lat, 'long' => $request->long, 'interest' => $request->interestedIn ?? 'rider']);

        if ($request->deviceToken && $request->deviceType) {
            $this->saveDevice($driver, $request);
        }




        if ($request->licenseNo) {
            $data = [
                'driver_id' => $driver->id,
                'type' => $request->type,
                'plate_no' => $request->plateNo,
                'license_no' => $request->licenseNo,
                'license' => $request->license,
                'picture' => $request->picture
            ];

            $document = $this->driverVehicleService->store($data);
            $vehicle = Vehicle::where('type', 'LIKE', '%' . strtolower($request->type) . '%')->first();
            $driver->vehicles()->attach($vehicle);
        }

        if ($request->subscriptionPackageId) {
            try {
                $package = SubscriptionPackage::find($request->subscriptionPackageId);
                $driver->packages()->attach($package);
                $driver->settlement()->create();

                if ($package->duration == "monthly") {

                    if ($driver->vehicles()->count() > 0) {
                        if ($driver->myVehicle()->type == "bike/scooter") {
                            $driver->settlement->update(['payable_amount' => $package->two_wheel_value]);
                        }

                        if ($vehicle->myVehicle()->type == "car/taxi") {
                            $driver->settlement->update(['payable_amount' => $package->four_wheel_value]);
                        }
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        try {
            if ($request->email) {
                // Mail::to($driver)->send(new DriverVerifyEmail($driver->email_token));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        //Validating code
        if (Str::contains($request->referCode, '#$')) {
            $voucher = Voucher::where('code', strtoupper($request->referCode))->first();
            if ($voucher) {
                if ($voucher->user->gogoWallet) {
                    $voucher->user->gogoWallet()->update(['amount' => $voucher->user->gogoWallet->amount + $voucher->amount]);
                } else {
                    $voucher->user->gogoWallet()->create(['amount' => $voucher->amount]);
                }
                $voucher->user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $voucher->amount, 'from' => 'Voucher',]);
            }
        } else {
            $this->isValidReferCode($request->referCode, $driver);
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

            // if ($driver->isBlocked()) {
            //     auth()->guard('driver-api')->logout();
            //     return failureResponse("Your Account has been blocked. Please contact to administrator.", 403, 403);
            // }

            if ($driver->blacklisted >= 3) {
                auth()->guard('driver-api')->logout();
                return failureResponse("Your Account has been blacklisted for " . $driver->blacklisted . " times. Please contact to administrator.", 403, 403);
            }


            if ($request->deviceToken && $request->deviceType) {
                $this->saveDevice($driver, $request);
            }

            //check for refercode 
            $this->referCode(auth()->guard('driver-api')->user());

            auth()->guard('driver-api')->user()->update(['last_login_at' => now()]);

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
        $driver->devices()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
    }

    public function verify($token)
    {
        try {
            Driver::where('email_token', $token)->firstOrFail()->verifyEmail();
        } catch (\Throwable $th) {
            return redirect('/')->with('failure_message', 'We cant process this action due to invalid token.');
        }
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
                // 'subscription' => $driver->packages()->count() > 0 ? $driver->currentPackage()->name : ($driver->subscription ?? 'No Subscription Type'),
                'subscription' => $driver->subscription ?? 'No Subscription Type',
                'verified' => $driver->isVerified(),
                'isPartiallyVerified' => $driver->isPartiallyVerified(),
                'rating' => $driver->averageRating(),
                'isBlocked' => $driver->is_blocked == 1,
                'blockedReason' => $driver->is_blocked == 1 ? $driver->log : '',
                'blackListed' => $driver->blacklisted ?? 0,
                'totalCompletedTrips' => $driver->completedTrips->count() + $driver->completedDeliveries()->count(),
                'completedTrips' => $driver->completedTrips()->whereDate('completed_at', date('Y-m-d'))->count(),
                'completedDeliveries' => $driver->completedDeliveries()->whereDate('delivered_at', date('Y-m-d'))->count(),
                'cancelledTrips' => $driver->cancelledTrips->count(),
                'payableAmount' => round($driver->settlement ? $driver->settlement->payable_amount : 0) + round($driver->settlement ? $driver->settlement->donation_amount : 0) + round($driver->inHouseRiderPayment()->sum('total') ? $driver->inHouseRiderPayment()->sum('total') : 0),
                'donationAmount' => round($driver->settlement ? $driver->settlement->donation_amount : 0),
                'receivableAmount' => round($driver->settlement ? $driver->settlement->receivable_amount : 0),
                'lifeTimeEarning' => round($driver->completedTrips->sum('price')),
                'totalEarning' => round($driver->completedTrips()->whereDate('completed_at', date('Y-m-d'))->sum('price')),
                'rideSoFar' => $driver->completedTrips->sum('distance1'),
                'isDocumentSubmitted' => $driver->isVerified() ? ($driver->documentState() == 0) : false,
                'documentState' => $driver->documentState() == 0,
                'interestedIn' => $driver->interested_in ?? "",
                'myReferCode' => $driver->refer_code ?? '',
                'rewardPoint' => $driver->reward_point,
                'referCount' => $driver->whoUsedMyCode()->count(),
                'usedCode' => $driver->used_code ?? '',
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

    public function isValidReferCode($code, $user)
    {
        try {
            if (strlen($code) < 4) {
                return false;
            } else {
                $refUser = Driver::where('refer_code', strtoupper($code))->first();
                if (!empty($refUser)) {
                    $defaultConf = DefaultConf::firstOrFail();
                    // $riderReferLimit = $this->conf['user_refer_limit'];
                    $riderReferLimit = $defaultConf->rider_refer_limit;

                    if ($refUser->whoUsedMyCode()->count() < $riderReferLimit) {
                        $usedBy = RiderReferral::create(['user_id' => $refUser->id, 'used_by' => $user->id]);
                        if ($defaultConf->rider_referral_user_point > 0) {
                            $refUser->update(['reward_point' => ($refUser->reward_point + $defaultConf->rider_referral_user_point)]);
                            $refUser->myNotifications()->create([
                                'title' => "Referral Point Received",
                                'message' => 'Congratulations! You have received ' . $defaultConf->rider_referral_user_point . ' points on successful installation of gogo20 referred by you.',
                                'type' => 'point',
                                'task' => $refUser->id
                            ]);
                        }

                        if ($defaultConf->rider_referred_user_point != 0) {
                            $user->update(['used_code' => $code, 'reward_point' => $defaultConf->rider_referred_user_point]);
                            $user->myNotifications()->create([
                                'title' => "Referred Point Received",
                                'message' => 'Congratulations! You have received ' . $defaultConf->rider_referred_user_point . ' points on successful installation of gogo20.',
                                'type' => 'point',
                                'task' => $user->id
                            ]);
                        }
                    } else {
                        $user->update(['used_code' => $code]);
                    }
                    return true;
                }
                return false;
            }
        } catch (\Throwable $th) {
            \Log::info("Error Message referral:" + $th->getMessage());
        }
    }

    // public function isValidReferCode($code, $driver)
    // {
    //     try {
    //         if (strlen($code) < 4) {
    //             return false;
    //         } else {
    //             $defaultConf = DefaultConf::firstOrFail();
    //             $riderReferLimit = $defaultConf->rider_refer_limit;
    //             if ($driver->whoUsedMyCode()->count() < $riderReferLimit) {
    //                 $refUser = Driver::where('refer_code', strtoupper($code))->first();
    //                 if (!empty($refUser)) {
    //                     $usedBy = RiderReferral::create(['driver_id' => $refUser->id, 'used_by' => $driver->id]);
    //                     $driver->update(['used_code' => $code]);
    //                     $refUser->update(['reward_point' => $refUser->reward_point + 25]);

    //                     return true;
    //                 }
    //                 return false;
    //             }
    //             return false;
    //         }
    //     } catch (\Throwable $th) {
    //         \Log::info("Error Message referral:" + $th->getMessage());
    //     }
    // }

    public function referCode($driver)
    {
        if (!$driver->refer_code) {
            $refer = "gogor" . $driver->id;
            $code = strtoupper($refer . randomAlphaNumericString(5));

            $driver->update(['refer_code' => substr($code, 0, 10)]);
        }
    }
}
