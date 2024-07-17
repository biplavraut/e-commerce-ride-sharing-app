<?php

namespace App\Http\Controllers\Api;

use App\Otp;
use App\User;
use App\RoadBlockMessage;
use App\OrderItem;
use App\UserDevice;
use Firebase\JWT\JWT;
use App\Mail\VerifyEmail;
use App\Custom\Sms\Twilio;
use Illuminate\Http\Request;
use App\Custom\Sms\AakashSms;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Api\UserQAResource;
use App\Http\Resources\Api\UserReviewResource;
use App\Http\Resources\Api\PastPurchaseProductResource;
use App\Custom\PushNotification;

class MiscController extends Controller
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        // parent::__construct();
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
        $getRoadBlockMessage = RoadBlockMessage::latest()->first();
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
                $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $request->phone, $message);
                $response = $sms->sendMessage();
                if (!$response) {
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
            return response()->json(['status' => false, 'message' => 'User Not Found.', 'existedUser' => false, 'statusCode' => 200], 200);
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

            $message = 'Dear ' . $user->name . ',';
            $message .= ' Your OTP for forget password is ' . $otp . '.';
            $message .= ' It is valid for 15 minutes.';
            $message .= ' Thank You!';
            $message .= ' Best Regards - Team gogo20 ' . $request->appId ?? '';

            if ($request->countryCode == "977") {
                $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $request->phone, $message);
                $response = $sms->sendMessage();
                if (!$response) {
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

        return response()->json([
            'data' => [
                'referCount' => $referCount,
                'myReferCode' => $user['refer_code'] ?? '',
                'rewardPoint' => $user['reward_point'] ?? 0,
                'totalSaved' => $user->totalSaved(),
                'rideSoFar' => round($user->completedTrips->sum('distance1')),
                'wishlistCount' => $wishlistCount,
                'gogoCash' => $user->gogoCash ? $user->gogoCash->amount : 0
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


        if (!($user->device) && $request->deviceType && $request->deviceToken) {
            if (UserDevice::where('device_token', $request->deviceToken)->count() == 0) {
                $user->device()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
                return successResponse("FCM Token added.");
            }
        } elseif (($user->device) && $request->deviceType && $request->deviceToken) {
            $userDevice = $user->device;
            if ($userDevice->device_token != $request->deviceToken) {
                $user->device()->update(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
                return successResponse("FCM Token updated.");
            }
        }

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
            'gender' => 'required|string|in:male,female,other',
            'dob' => 'required|string',
            'address' => 'required|string',
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
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email' => $request->email,
            'address' => $request->address,
            'office' => $request->officeAddress,
            'lat' => $request->lat,
            'long' => $request->long
        ]);

        if ($request->image) {
            $user = $this->userService->update($user->id, $request->only('image'));
        }

        if ($request->email && !$user->isVerified()) {
            try {
                Mail::to($user)->send(new VerifyEmail($user->email_token));
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
        $orderItems = OrderItem::where('user_id', $user->id)->select(['product_id'])->distinct('product_id')->get();

        return PastPurchaseProductResource::collection($orderItems)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function generateToken(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $service_account_email = "firebase-adminsdk-i5bzb@gogo20-292702.iam.gserviceaccount.com";
        $private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCYKNaVS1PSuTNN\noiKdqK6Negpvz+LL1oFNQC1W3DgoRfsC0jBzDJRZuojx3dNqzMA9z5XInlpN4RLz\ngv/3ag+9G3d+UfIiTXGVTbymrQo6g17xv+UEEbTntxkeq39Unr7h5nkLoiJ9c8nK\nvYXiOOo/Y9N4dz6PUJZjTIADgarW3Ej21g53UxGxNB72r38zGMVv4ro23CCCDykx\nj7rDFpwaUzUXklCclhW9GrEVyhQuP03VaX15OklzXkaChD5jr3J/2ZmoCkbOUZcF\ngrwimynC0xKOf88Xi14AVGP99B4UuSihxA1THOX6mfAaewZ2kgC4tiQz8caIcHWv\npb5+Tt4zAgMBAAECggEABxaIuUERjg4au2gtBNTs3XuzM1zNQiHJ0y+FDStKbs6k\nialnhvlHV3WB8WmcDB97aXjoKTjLMlG+RsHlXgELhT7CAb+CfO7IaddkGgv5UoF4\nV+DQo8n8keC5ifBP1aeX2VqzOWoCAX/cnuLWP+DXDkEYHEZkKSi5YLXMrtBVa33N\npt40G1RyIIMgUuAHniiawrkaJsy0FB7r6JUc1TJIrxIq53feV913R4lDBlZSsm6R\nynTqABN2okeW7keofejdxr/Y+gwzGIcFkGItV84qmbJIixPr9bIWyMHUcPpKX877\nYrnxHFcE9M51oPzMh0OgfzmAUo9AIeHaTWazvd8WcQKBgQDVCemIpPGDCAE1qq3m\nCXmRTbVkPy6oOoaaYqjSGCOu3GJNd6HlgbykhxQHMTOYkAq5sA1FMNoFR99PeOiT\nzYbCR0b8xQGPuEL7yWceHAhgTbtCzbOFRuGXK6e7whZmrTfKOCFB5km1v2dny+3l\nCfwnB/nNr5oTFqHdXEgkww5AowKBgQC22Aw/QVmYPZvcX/FBb02i9kHS2iXUDEgc\n8Qu8jzd/Ea2fMksDl4HLJ0XEgAZ3zElOQDfuX7/X0VEP4uBjPL+JJtop8pdQmMBj\nL5W9tRuWRo4V00p4QJrpMu0KejWZpPUuVoo2tdfVkuIHaxFkq5scfjrryjzcGDWj\nEBr7lW51MQKBgQCs7BrPe3M99KVmtl/pNQ+kTftKn65zhu3zKtn1jvqH2QNB9jVH\nYYOJ2Mr8+4bx8xmBl9FttDWDy88LZw0By/XyhICudArMabiVP+mfmwmBghbaJrXt\nHJfIaPsBgI1GUpvSXLVCFHcIO5Dnw7QaEXzHAcZWmo7pp5lDYMB+doV/GQKBgBq7\nr963wU+/AkDQTkfQ7Dr9YlZfytQcD5cbrymcjvKnNQlnowwdZL69OTgnt8pgNf+Y\n6BSUL0pwsjduZnxb720wHwmvDGyeSNK3rF8WUbhBDJkoUWUPnRaneXzkrV2PfsGN\nqZuiLrJtTVrkTqC3bnBWDGtZIFjVuVHkEu3hxUqhAoGATLi9iSRWvKfcUL3irqTQ\nxYCj/rLJQu+NWOfgw7q5ez0bd6yw6eonkl+XPdJSz0a+DjHRZgHuMJjZCOHOFK3x\nrPXRf3Z0tKYwuYYP5cStQPXo95VstapYAaDYuyK9C1aN3vPyjkPcRRSe3sEZBFMi\nUsFZTCBfBy7Gh1ZqbYnnTy0=\n-----END PRIVATE KEY-----\n";
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
}
