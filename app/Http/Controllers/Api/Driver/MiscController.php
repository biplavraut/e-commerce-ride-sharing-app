<?php

namespace App\Http\Controllers\Api\Driver;

use App\Otp;
use App\Driver;
use App\DriverDevice;
use Firebase\JWT\JWT;
use App\Custom\Sms\Twilio;
use App\Custom\Sms\Sparrow;
use Illuminate\Http\Request;
use App\Custom\Sms\AakashSms;
use App\Mail\DriverVerifyEmail;
use App\Services\DriverService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Driver\DriverResource;

class MiscController extends Controller
{
    /** @var DriverService */
    private $driverService;

    public function __construct(DriverService $driverService)
    {
        // parent::__construct();
        $this->driverService = $driverService;
    }

    public function sendOtp(Request $request)
    {
        $user = Driver::where('phone', $request->phone)->Where('country_code', "+" . $request->countryCode)->first();

        if ($user) {
            return response()->json(['status' => true, 'message' => 'Already Registered.', 'existedUser' => true, 'statusCode' => 200]);
        }

        $oldOtp = Otp::where('phone', '+' . $request->countryCode . $request->phone)->first();

        if ($oldOtp) {
            $oldOtp->delete();
        }

        $otp = randomNumericString(6);
        try {
            Otp::create(['otp' => $otp, 'phone' => '+' . $request->countryCode . $request->phone]);

            $message = 'Dear gogoRider,';
            $message .= 'Welcome to Everyday Solution. Now,';
            $message .= ' Your OTP is ' . $otp . '.';
            $message .= ' It is valid for 15 minutes.';
            $message .= ' Thank You! ';
            $message .= ' Best Regards - Team gogo20'.$request->appId ?? '';

            if ('+' . $request->countryCode == "+977") {
                $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $request->phone, $message);
                $response = $sms->sendMessage();
                if (!$response) {
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => false, 'statusCode' => 200], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Aakash SMS Error', 'statusCode' => 422], 422);
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
            return response()->json(['status' => false, 'message' => 'error', 'existedUser' => false, 'statusCode' => 422], 422);
        }
    }

    public function ForgetPasswordOtp(Request $request)
    {
        $user = Driver::where('phone', $request->phone)->Where('country_code', "+" . $request->countryCode)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User Not Found.', 'existedUser' => false, 'statusCode' => 200], 200);
        }

        $oldOtp = Otp::where('phone', '+' . $request->countryCode . $request->phone)->first();

        if ($oldOtp) {
            $oldOtp->delete();
        }

        $otp = randomNumericString(6);
        try {
            Otp::create(['otp' => $otp, 'phone' => '+' . $request->countryCode . $request->phone]);

            $message = 'Dear ' . $user->first_name . ',';
            $message .= ' Your OTP for forget password is ' . $otp . '.';
            $message .= ' It is valid for 15 minutes.';
            $message .= ' Thank You!';
            $message .= $request->appId ?? '';

            if ('+' . $request->countryCode == "+977") {
                $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $request->phone, $message);
                $response = $sms->sendMessage();
                if (!$response) {
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => false, 'statusCode' => 200], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'Aakash SMS Error', 'statusCode' => 422], 422);
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

        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }


        if (Hash::check($request->currentPassword, $driver->password)) {
            if ($driver->update(['password' => bcrypt($request->newPassword)])) {
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
        $user = Driver::where('phone', $request->phone)->Where('country_code', '+' . $request->countryCode)->first();

        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        if ($user->update(['password' => bcrypt($request->password)])) {
            return successResponse("Your password has been set successfully.");
        }
        return failureResponse("Sorry, your password could not be set. Please try again later.", 418, 418);
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

        $driver  = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }


        if (!($driver->device) && $request->deviceType && $request->deviceToken) {
            if (DriverDevice::where('device_token', $request->deviceToken)->count() == 0) {
                $driver->device()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
                return successResponse("FCM Token added.");
            }
        } elseif (($driver->device) && $request->deviceType && $request->deviceToken) {
            $driverDevice = $driver->device;
            if ($driverDevice->device_token != $request->deviceToken) {
                $driver->device()->update(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
                return successResponse("FCM Token updated.");
            }
        }

        return successResponse("No change in FCM Token.");
    }

    public function updateProfile(Request $request)
    {
        $driver  = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'gender' => 'required|string|in:male,female,other',
            'dob' => 'required|string',
            'address' => 'required|string',
            'image' => 'nullable|file|max:2048|mimes:jpg,jpeg,png',
            'interestedIn' => 'nullable|string',
            'email' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'subscription' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $driver->update([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'email' => $request->email,
            'address' => $request->address,
            'interested_in' => $request->interestedIn,
            'lat' => $request->lat,
            'subscription' => $request->subscription ?? $driver->subscription,
            'long' => $request->long
        ]);

        if ($driver->status) {
            $status = $driver->status()->update(
                [
                    'interest' => $request->interestedIn ?? 'rider'
                ]
            );
        }

        if ($request->image) {
            $driver = $this->driverService->update($driver->id, $request->only('image'));
        }

        if ($request->email && !$driver->isEmailVerified()) {
            try {
                Mail::to($driver)->send(new DriverVerifyEmail($driver->email_token));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }


        return (new DriverResource($driver))->additional(['status' => true, 'message' => "Profile updated.", 'statusCode' => 200], 200);
    }

    public function status(Request $request)
    {
        $driver  = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $validator = Validator::make($request->all(), [
            'lat' => 'required|string',
            'long' => 'required|string',
            'status' => 'nullable|in:active,inactive',
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
                    'status' => $driver->status->status != 'ongoing' ? $request->status : 'ongoing'
                ]
            );

            return successResponse('Status Updated.', 200, 200);
        }

        $status = $driver->status()->create(
            [
                'lat' => $request->lat,
                'long' => $request->long,
                'bearing' => $request->bearing ?? null,
                'interest' => $driver->interested_in ?? 'rider',
                'status' => $request->status,
                'updated_at' => now()
            ]
        );
        return successResponse('Status Created.', 200, 200);
    }

    public function generateToken(Request $request)
    {
        $driver  = auth()->guard('driver-api')->user();

        if (!$driver) {
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
            "uid" => 'rider_' . $driver->id,
            "claims" => array(
                "premium_account" => $is_premium_account
            )
        );

        $token =  JWT::encode($payload, $private_key, "RS256");

        return response()->json([
            'data' => [
                'uid' => 'rider_' . $driver->id,
                'chatToken' => $token,
                'expireIn' => $now_seconds + (60 * 60)
            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }
}
