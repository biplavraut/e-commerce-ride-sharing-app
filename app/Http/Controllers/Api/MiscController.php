<?php

namespace App\Http\Controllers\Api;

use App\Otp;
use App\User;
use App\OrderItem;
use App\UserDevice;
use Firebase\JWT\JWT;
use App\AcademySlider;
use App\AcademyContent;
use App\Mail\VerifyEmail;
use App\RoadBlockMessage;
use App\AdditionalService;
use App\Custom\Sms\Twilio;
use App\Custom\Sms\Sparrow;
use App\GlobalNotification;
use Illuminate\Http\Request;
use App\Custom\Sms\AakashSms;
use App\Services\UserService;
use App\Custom\PushNotification;
use App\GogoAd;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\AdResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Api\UserQAResource;
use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Api\UserReviewResource;
use App\Http\Resources\Api\AcademySliderResource;
use App\Http\Resources\Api\AcademyContentResource;
use App\Http\Resources\Api\AdditionServiceResource;
use App\Http\Resources\Api\PastPurchaseProductResource;
use App\Order;
use App\OrderOfferConf;
use App\Trip;

class MiscController extends CommonController
{
    /** @var UserService */
    private $userService;

    protected $conf = [];


    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    //This function is only used for firebase testing
    public function firebase()
    {
        $token = $_GET['token'];
        $notification = new PushNotification(
            [$token],
            // ["dxWagfGuTpuyBgvmrGxjgH:APA91bENyZ8C0UtAKQtjqx1azZ-pi_YEUiTU28J916PBbpNds7MTB4fhcy2rVmTNOBgli3_eTtjzWfuep0XISX92ZWlD-F5yEiNugjdpr-6s8z74g_s3lMySYG4T-4gaxlrMZ11ZOEkw"],
            [
                'title' => 'Testing firebase message',
                'body' => 'Firebase testing message body',
                'type' => 'delivery_assigned'
            ]
        );
        $response = $notification->send();
    }

    public function roadBlockMessage()
    {
        $getRoadBlockMessage = RoadBlockMessage::where('type', 'user')->latest()->first();
        if (!empty($getRoadBlockMessage) && $getRoadBlockMessage->status == 1) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $getRoadBlockMessage, 'statusCode' => 200], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'success', 'data' => null, 'statusCode' => 200], 200);
        }
    }

    public function sendOtp(Request $request)
    {
        \Log::info("----------Send OTP Start From here");
        \Log::info($request->all());
        $user = User::where('phone', $request->phone)->Where('country_code', "+" . $request->countryCode)->first();

        if ($user) {
            return response()->json(['status' => true, 'message' => 'Already Registered.', 'existedUser' => true, 'statusCode' => 200]);
        }

        $oldOtp = Otp::where('phone', '+' . $request->countryCode . $request->phone)->first();

        if ($oldOtp) {
            $oldOtp->delete();
        }

        $otp = randomNumericString(6);

        while (!$this->generateOtp($otp)) {
            $otp = randomNumericString(6);
        }


        try {
            \Log::info("----------OTP Details------");
            \Log::info($request->countryCode);
            \Log::info($request->phone);
            Otp::create(['otp' => $otp, 'phone' => '+' . $request->countryCode . $request->phone]);

            $message = 'Dear gogoUser,';
            $message .= 'Welcome to Everyday Solution. Now,';
            $message .= ' Your OTP is ' . $otp . '.';
            $message .= ' It is valid for 15 minutes.';
            $message .= ' Thank You! ';
            $message .= ' Best Regards - Team gogo20 ' . $request->appId ?? '';

            if ($request->countryCode == "977") {
                $sms = new Sparrow($request->phone, $message);
                $response = $sms->sendMessage();

                if ($response == 200) {
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => false, 'statusCode' => 200], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'SMS Error', 'statusCode' => 422], 422);
                }
            } else {
                try {
                    $twilio = new Twilio('+' . $request->countryCode . $request->phone, $message);
                    $response = $twilio->sendMessage();
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => false, 'statusCode' => 200], 200);
                } catch (\Throwable $th) {
                    return response()->json(['status' => false, 'message' => 'Twilio Error', 'statusCode' => 422], 422);
                }
            }
        } catch (\Throwable $th) {
            recordLog("SMSLOG", true, true, $log = $th->getMessage());
            return response()->json(['status' => false, 'message' => 'error', 'existedUser' => false, 'statusCode' => 422], 422);
        }
    }

    public function ForgetPasswordOtp(Request $request)
    {
        $user = User::where(['phone' => $request->phone, 'country_code' => "+" . $request->countryCode])->first();

        if (!$user) {
            return response()->json(['status' => true, 'message' => 'User Not Found.', 'existedUser' => false, 'statusCode' => 200], 200);
        }

        $oldOtp = Otp::where('phone', '+' . $request->countryCode . $request->phone)->first();

        if ($oldOtp) {
            $oldOtp->delete();
        }

        $otp = randomNumericString(6);

        while (!$this->generateOtp($otp)) {
            $otp = randomNumericString(6);
        }

        try {
            Otp::create(['otp' => $otp, 'phone' => '+' . $request->countryCode . $request->phone]);

            $message = 'Dear ' . $user->first_name . ",\n";
            $message .= ' Your OTP for forget password is ' . $otp . '.';
            $message .= ' It is valid for 15 minutes.';
            $message .= "\n" . 'Thank You!';
            $message .= "\n" . ' Best Regards - Team gogo20 ' . $request->appId ?? '';

            if ($request->countryCode == "977") {
                $sms = new Sparrow($request->phone, $message);
                $response = $sms->sendMessage();

                if ($response == 200) {
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => true, 'statusCode' => 200], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'SMS Error', 'statusCode' => 422], 422);
                }
            } else {
                try {
                    $twilio = new Twilio('+' . $request->countryCode . $request->phone, $message);
                    $response = $twilio->sendMessage();
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => true, 'statusCode' => 200], 200);
                } catch (\Throwable $th) {
                    return response()->json(['status' => false, 'message' => 'Twilio Error', 'statusCode' => 200], 200);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'error', 'existedUser' => true, 'statusCode' => 422], 422);
        }
    }

    public function verifyOtp(Request $request)
    {
        \Log::info("----------OTP Verify ------");
        \Log::info($request->countryCode);
        \Log::info($request->phone);
        \Log::info($request->otp);

        if ($this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone)) {
            return successResponse("Success.");
        }
        return failureResponse("otp doesnot match", 404, 404);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }


        if (Hash::check($request->currentPassword, $user->password)) {
            if ($user->update(['password' => bcrypt($request->newPassword)])) {
                return successResponse("Your password has been changed successfully.");
            } else {
                return failureResponse("Sorry, your password could not be changed. Please try again later.", 418, 418);
            }
        }
        return failureResponse("Your old password did not match. Please try again.", 418, 418);
    }

    public function updateForgetPassword(Request $request)
    {
        if (!$this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone)) {
            return failureResponse("otp doesnot match", 404, 404);
        }
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
            'phone' => 'required|string',
            'countryCode' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $user = User::where('phone', $request->phone)->Where('country_code', '+' . $request->countryCode)->first();

        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        if ($user->update(['password' => bcrypt($request->password)])) {
            return successResponse("Your password has been set successfully.");
        }
        return failureResponse("Sorry, your password could not be set. Please try again later.", 418, 418);
    }

    public function verifyMyOtp($otp, $phone)
    {
        $isExist = Otp::where('otp', $otp)->Where('phone', $phone)->first();
        if ($isExist) {
            return true;
        }
        return false;
    }

    public function myProfile()
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $referCount = $user->whoUsedMyCode()->count();
        $wishlistCount = $user->wishlist()->count();

        // Statr of Order Offer Discount
        // Check Active order offers
        $offerActive = false;
        $offerTitle = "";
        $isEligible = false;
        $offerDiscount = 0;
        $remainingOrderOffer = 0;
        $activeOrderOffer = OrderOfferConf::where('enabled', 1)
            ->where('from', '<=', date('Y-m-d H:i:s'))
            ->where('to', '>', date('Y-m-d H:i:s'))->first();
        $orders = Order::where('user_id', $user->id);
        $totalOrders = $orders->distinct()->count('ref_number');
        $orderDelivered = $orders->distinct()->where('status', 'DELIVERED')->count('ref_number');
        $trips = Trip::where('user_id', $user);
        $totalTrips =  $trips->count();
        $tripsCompleted = $trips->where('status', 'completed')->count();
        if ($activeOrderOffer) {
            // Check if user is eligible for offer
            $countOrders = Order::where('user_id', $user->id)
                ->distinct()
                ->where('status', '!=', 'CANCELLED')
                ->where('created_at', '>=', $activeOrderOffer->from)
                ->where('created_at', '<=', $activeOrderOffer->to)->count('ref_number');
            $remainingOrders = $activeOrderOffer->no_of_orders - $countOrders;
            if ($remainingOrders > 0) {
                $offerActive = true;
                $offerTitle = $activeOrderOffer->order_title;
                $isEligible = true;
                $offerDiscount = $activeOrderOffer->discount;
                $remainingOrderOffer = $remainingOrders;
            } else {
                $offerActive = true;
                $offerTitle = $activeOrderOffer->order_title;
                $isEligible = false;
                $offerDiscount = $activeOrderOffer->discount;
                $remainingOrderOffer = 0;
            }
        }
        // End of checking order offer
        return response()->json([
            'data' => [
                'eliteUser' => $user->elite == 1,
                'myId' => $user->userId(),
                'referCount' => $referCount,
                'myReferCode' => $user['refer_code'] ?? '',
                'rewardPoint' => $user['reward_point'] ?? 0,
                'totalSaved' => $user->totalSaved(),
                'rideSoFar' => round($user->completedTrips->sum('distance1')),
                'wishlistCount' => $wishlistCount,
                'gogoWallet' => $user->gogoWallet ? round($user->gogoWallet->amount + $user['reward_point']) : round(0 + $user['reward_point']),
                'orderOffer' => $offerActive,
                'offerTitle' => $offerTitle,
                'isEligible' => $isEligible,
                'orderOfferDiscount' => $offerDiscount,
                'remainingOffer' => $remainingOrderOffer,
                'userSince' => 'User Since: ' . date_format($user->created_at, "F, Y"),
                'totalOrder' => $totalOrders,
                'orderDelivered' => $orderDelivered,
                'totalRides' => $totalTrips,
                'ridesCompleted' => $tripsCompleted,
                'totalTransaction' => $totalOrders + $totalTrips
            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }

    public function myReviews()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return UserReviewResource::collection($user->reviews)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function myQAs()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return UserQAResource::collection($user->qas)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function fcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deviceType' => 'required|string|in:android,ios',
            'deviceToken' => 'required|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }


        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if ($request->deviceType && $request->deviceToken) {
            $user->devices()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
            return successResponse("FCM Token added.");
        }


        // if (!($user->device) && $request->deviceType && $request->deviceToken) {
        //     if (UserDevice::where('device_token', $request->deviceToken)->count() == 0) {
        //         $user->device()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
        //         return successResponse("FCM Token added.");
        //     }
        // } elseif (($user->device) && $request->deviceType && $request->deviceToken) {
        //     $userDevice = $user->device;
        //     if ($userDevice->device_token != $request->deviceToken) {
        //         $user->device()->update(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
        //         return successResponse("FCM Token updated.");
        //     }
        // }

        return successResponse("No change in FCM Token.");
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            // 'gender' => 'nullable|string|in:male,female,other',
            'dob' => 'nullable|string',
            'address' => 'nullable|string',
            'officeAddress' => 'nullable|string',
            'image' => 'nullable|file|max:2048|mimes:jpg,jpeg,png',
            'email' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $user->update([
            'first_name' => $request->firstName ?? $user->first_name,
            'last_name' => $request->lastName ?? $user->last_name,
            // 'gender' => $request->gender ?? $user->gender,
            'dob' => $request->dob ?? $user->dob,
            'email' => $request->email ?? $user->email,
            'address' => $request->address ?? $user->address,
            'office' => $request->officeAddress ?? $user->office,
            'lat' => $request->lat,
            'long' => $request->long
        ]);


        if ($user->myAddress) {
            $user->myAddress()->update([
                'district' => $request->district ?? $user->myAddress->district,
                'municipality' => $request->municipality ?? $user->myAddress->municipality,
                'ward' => $request->ward ?? $user->myAddress->ward,
                'organization' => $request->organization ?? $user->myAddress->organization
            ]);
        } else {
            $user->myAddress()->create([
                'district' => $request->district,
                'municipality' => $request->municipality,
                'ward' => $request->ward,
                'organization' => $request->organization
            ]);
        }


        if ($request->image) {
            $user = $this->userService->update($user->id, $request->only('image'));
        }

        if ($request->email && !$user->isVerified()) {
            try {
                // Mail::to($user)->send(new VerifyEmail($user->email_token));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        return (new UserResource($user))->additional(['status' => true, 'message' => "Profile Updated.", 'statusCode' => 200], 200);
    }

    public function pastPurchase()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $orderItems = OrderItem::where('user_id', $user->id)->whereHas('order', function ($query) {
            $query->where('status', 'DELIVERED');
        })->select(['product_id'])->distinct('product_id')->get();

        return PastPurchaseProductResource::collection($orderItems)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function generateToken(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $service_account_email = "firebase-adminsdk-i5bzb@gogo20-292702.iam.gserviceaccount.com";
        $private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDPDEvlCvpeaHu2\ni79J5VDPZ2xN7VUHNKoAVCpgRlmUCFHRcu5PPMyddSTc5AIWjNk0tt+zGaFYz8mA\n1WPAb80xg66RvGdOjsD+lEg87vNNSidKaeusRxeg0XlXfqAqcQ0lRlidajQWz8iV\nGEdax2l7hoGu/AfAWMcXnoUBVoQnctsxvfkky9loguReise4+VYpT8Eb7l7i1/s3\nacwuAapaYH+HBhml6U+FFc/F8OvtP2qEynhzaLy5XaVvQPjY1b8yauoZOz7PxxfG\naYriTwPt40qrmdglc9VAe+rSVXZJBCvAfWdFO4jZuIdGqQ30JD/s/nWifHN2m8HC\ncuAqnl9TAgMBAAECggEAVA+6AJQkcpAZKlQypd3koVBsOHdTPkmze86wJcZqOB/o\nmBEBkEovCP62qLgp4N3ukehtilmtmFFEjnoUtdyugpvkFGdZyhtLYBD0A0lAnvCs\nYxn+BUQX48MocM2IWbLsk2WsNL0ZyOkzltT0o3ay7OP/YvwQaZehvYUumwx1O046\n5OvDQa6JzVdyvHArkcIB0bIZxB0B/lkXrjYN0fVbENzcPFSP46TKKTXzTajURYUX\nBAg2VhEYp/rDO9NNCILdVHGqp8eMQPiEJYQ14WS9cpVWJE6OCgOQcTqxcS3pOEMx\nETkXr7ymaFAXqJxonEF2pt6zYljBF0jNaIHzzeeggQKBgQD7YRDRUHSHQt0gMh3p\nn8rUTHa1pn7PvbVHzG7msHD6nm3ruu/+CVE+HXOVkL40HPSq6LKgBhSGmUYVR8uA\npLcM6k7UwIyxHhlnYrdo1HpvWKpTJ0T/ESjVeBWCHOwBNKoXOXm99i74fqIebPai\n1u0bBmnD0kl41q/w0gw2aISPBwKBgQDS2p5Mfc9vvjBPc1zvUimL/KzodHJs3IYW\nVIbdaJlV7ZfvdMujToCJV599cbu0cWAt5WAYyjyEZI/V0zEJGaS4hdlIXPiPJsen\nya1AU3ZhjwnHFl/BSk9c3p0ve7DwUzGBKmZAFW8t8PYVLY3sVthxosvcIua/DNCG\nHL3T8FOOVQKBgCWCV1MUUT75oCs0rzf0Cvzgp/n07QksgodDHu03OLR8vWQmUbcz\nRrchB+UyYt89zthNvpGYNqna5xU30ErSySmZMMgYLyYimqHNnhZ7VgWVUlz0CByT\nP+eScosmq6SGajbq8ZqByIJ1ytUFZ0vnDBwzOcbpcuMgDcK+ZOodcGIzAoGANwnv\n9kGE60M28xZG9QV6aNsc+1SiJb5uldecmKDcHaz2UwAmw8sTDEa+EA2nFJPfqjtM\nmUv4/goN7Z/CFgEGmU//Br+V9jAGP8sKGVdv+ElRIRG7DwZprvFBEIr2BdWBf5jk\nteeibNiQM7j/aejFeOwo0J6fotEigU9xUahS9z0CgYEAns+TOcLujIj/ifMcWUkh\nHuKx1KvAYCyPotvkj0ZRMfBOnAN5xJQxAobEFsKk1zFxvFCHxiV9tgfX870j9Qll\n2sCkxCVDs/UOcQQK+grus7Z+K6VVGoWV6N3fnIUobjwgNDTTT0yiaUdxDu7/2f/c\n8yQUWyl1jonE+IbsqDhUo2o=\n-----END PRIVATE KEY-----\n";
        $is_premium_account = false;

        $now_seconds = time();
        $payload = array(
            "iss" => $service_account_email,
            "sub" => $service_account_email,
            "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
            "iat" => $now_seconds,
            "exp" => $now_seconds + (60 * 60), // Maximum expiration time is one hour
            "uid" => 'user_' . $user->id,
            "claims" => array(
                "premium_account" => $is_premium_account
            )
        );

        $token =  JWT::encode($payload, $private_key, "RS256");

        return response()->json([
            'data' => [
                'uid' => 'user_' . $user->id,
                'chatToken' => $token,
                'expireIn' => $now_seconds + (60 * 60)
            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }

    public function generateOtp($otp)
    {
        $isExist = Otp::where('otp', $otp)->first();

        if ($isExist) {
            return false;
        }
        return true;
    }

    public function globalConf()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $referCount = $user->whoUsedMyCode()->count();
        $wishlistCount = $user->wishlist()->count();

        $scheduleTrips = $user->trips()->whereHas('schedule')->Where('status', '!=', 'completed')->count();
        $pendingCahback = $user->pendingCashback->where('status', 'PENDING')->sum('order_cashback');

        $rewardPoint = $user['reward_point'] ?? 0;

        return response()->json([
            'data' => [
                'eliteUser' => $user->elite == 1,
                'myId' => $user->userId(),
                'referCount' => $referCount,
                'myReferCode' => $user['refer_code'] ?? '',
                'rewardPoint' =>  $user['reward_point'] ?? 0,
                // 'reward' => hash('sha512', $user['reward_point']),
                'totalSaved' => $user->totalSaved(),
                'rideSoFar' => round($user->completedTrips->sum('distance1')),
                'wishlistCount' => $wishlistCount,
                'gogoWallet' => $user->gogoWallet ? round($user->gogoWallet->amount) : 0,
                'pendingCashback' => $pendingCahback ?? 0,
                'freeDeliveryAfter' => $this->conf['free_delivery_after'],
                'deliveryCharge' => $this->conf['delivery_charge'],
                'deliveryChargeOutside' => $this->conf['delivery_charge_outside'],
                'scheduleTrips' => $scheduleTrips,
                'globalNotificationCount' => GlobalNotification::where('for', 'end-user')
                    ->where('sent', 1)
                    ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
                    ->count(),
                'userNotificationCount' => $user->myNotifications()->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->count(),
                'cashbackPercent' => $this->conf['cashback_percent'] > 0 ? $this->conf['cashback_percent'] : 0,
                'purchaseOf' => $this->conf['purchase_total'] > 0 ? $this->conf['purchase_total'] : 0,
                'appLatestVersion' => $this->conf['user_app_version'],
                'appMinorUpdate' => $this->conf['user_app_minor'],
                'appMajorUpdate' => $this->conf['user_app_major'],
                'paymentOptions' => [
                    [
                        "id" => 1,
                        "name" => "gogoPoint",
                        "value" => "gogoWallet"
                    ],
                    [
                        "id" => 2,
                        "name" => "Khalti",
                        "value" => "khalti"
                    ],
                    [
                        "id" => 3,
                        "name" => "eSewa",
                        "value" => "esewa"
                    ],
                    [
                        "id" => 4,
                        "name" => "Cash On Delivery",
                        "value" => "cod"
                    ]
                ],
                'utilityPromo' => $this->conf['utility_promo'] ?? '',
                'userBirthday' => date('m-d') == date('m-d', strtotime($user->dob)),
                'rewardRedeemLimit' => $this->conf['reward_redeem_limit'] ?? '',
                'redeemTitle' => 'You can redeem ' . $this->conf['reward_redeem_limit'] . '% of Total Amount from your ' . $rewardPoint . ' gogoRewards in this transaction.',
                // 'redeemTitle' => 'Redeem ' . $this->conf['reward_redeem_limit'] . '% from your ' . $rewardPoint . ' gogoPoints?',
                'minOrderLimit' => $this->conf['min_order_limit'] ?? '',
                'userReferTitle' => $this->conf['user_refer_text'] ?? "Refer your friend",
                'poolBikePerKmPerSeat' => $this->conf['pool_bike_per_km_per_seat'],
                'poolCarPerKmPerSeat' => $this->conf['pool_car_per_km_per_seat'],
            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }

    public function default()
    {
        return response()->json([
            'data' => [
                'appLatestVersion' => $this->conf['user_app_version'],
                'appMinorUpdate' => $this->conf['user_app_minor'],
                'appMajorUpdate' => $this->conf['user_app_major'],
                'paymentOptions' => [
                    [
                        "id" => 1,
                        "name" => "gogoPoint",
                        "value" => "gogoWallet"
                    ],
                    [
                        "id" => 2,
                        "name" => "Khalti",
                        "value" => "khalti"
                    ],
                    [
                        "id" => 3,
                        "name" => "eSewa",
                        "value" => "esewa"
                    ],
                    [
                        "id" => 4,
                        "name" => "Cash On Delivery",
                        "value" => "cod"
                    ]
                ],
                'utilityPromo' => $this->conf['utility_promo'] ?? '',

            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }

    public function exclusiveServices()
    {
        $services = AdditionalService::where('enabled', 1)->orderBy('order')->get();
        $ads = GogoAd::where('hide', 0)->where('type', 'utility')->get();

        return response()->json([
            'data' => [
                "sliders" => AdResource::collection($ads),
                "services" => AdditionServiceResource::collection($services),
            ],
            'status' => true,
            'message' => '',
            'statusCode' => 200
        ], 200);
    }

    public function checkoutSlider()
    {
        $ads = GogoAd::where('hide', 0)->where('type', 'checkout')->get();

        return response()->json([
            'data' => [
                "sliders" => AdResource::collection($ads),
            ],
            'status' => true,
            'message' => '',
            'statusCode' => 200
        ], 200);
    }

    public function academy(Request $request)
    {
        $sliders = AcademySlider::where('fors', 'user')->latest()->get();
        $contents = AcademyContent::where('fors', 'user')->latest()->get();

        return response()->json([
            'data' => [
                "sliders" => AcademySliderResource::collection($sliders),
                "contents" => AcademyContentResource::collection($contents),
            ],
            'status' => true,
            'message' => '',
            'statusCode' => 200
        ], 200);
    }
}
