<?php

namespace App\Http\Controllers\Api\Driver;

use App\Otp;
use App\Driver;
use App\DefaultConf;
use App\DriverDevice;
use Firebase\JWT\JWT;
use App\AcademySlider;
use App\AcademyContent;
use App\RoadBlockMessage;
use App\Custom\Sms\Twilio;
use App\Custom\Sms\Sparrow;
use App\GlobalNotification;
use App\SubscriptionPackage;
use Illuminate\Http\Request;
use App\Custom\Sms\AakashSms;
use App\Mail\DriverVerifyEmail;
use App\Services\DriverService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Driver\DriverResource;
use App\Http\Resources\Api\AcademySliderResource;
use App\Http\Resources\Api\AcademyContentResource;
use App\Http\Resources\Api\Driver\SubscriptionPackageResource;

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
            $message .= ' Best Regards - Team gogo20' . $request->appId ?? '';

            if ('+' . $request->countryCode == "+977") {
                $sms = new Sparrow($request->phone, $message);
                $response = $sms->sendMessage();

                if ($response == 200) {
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
            return response()->json(['status' => true, 'message' => 'User Not Found.', 'existedUser' => false, 'statusCode' => 200], 200);
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

                $sms = new Sparrow($request->phone, $message);
                $response = $sms->sendMessage();

                if ($response == 200) {
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => true, 'statusCode' => 200], 200);
                } else {
                    return response()->json(['status' => true, 'message' => 'SMS Error', 'statusCode' => 422], 422);
                }
            } else {
                try {
                    $twilio = new Twilio('+' . $request->countryCode . $request->phone, $message);
                    $response = $twilio->sendMessage();
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => true, 'statusCode' => 200], 200);
                } catch (\Throwable $th) {
                    return response()->json(['status' => true, 'message' => 'Twilio Error', 'statusCode' => 200], 200);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => true, 'message' => 'error', 'existedUser' => true, 'statusCode' => 422], 422);
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

        if ($request->deviceType && $request->deviceToken) {
            $driver->devices()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
            return successResponse("FCM Token added.");
        }


        // if (!($driver->device) && $request->deviceType && $request->deviceToken) {
        //     if (DriverDevice::where('device_token', $request->deviceToken)->count() == 0) {
        //         $driver->device()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
        //         return successResponse("FCM Token added.");
        //     }
        // } elseif (($driver->device) && $request->deviceType && $request->deviceToken) {
        //     $driverDevice = $driver->device;
        //     if ($driverDevice->device_token != $request->deviceToken) {
        //         $driver->device()->update(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
        //         return successResponse("FCM Token updated.");
        //     }
        // }

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
            'district' => 'bail|nullable|string|max:255',
            'municipality' => 'bail|nullable|string|max:255',
            'ward' => 'bail|nullable|max:255',
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
                // Mail::to($driver)->send(new DriverVerifyEmail($driver->email_token));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        if ($driver->address()->first()) {
            $address = $driver->address()->first()->update(
                [
                    'district' => $request->district,
                    'municipality' => $request->municipality,
                    'ward' => $request->ward
                ]
            );
            $message  = "Home address updated successfully.";
        } else {
            $address = $driver->address()->create(
                [
                    'driver_id' => $driver->id,
                    'district' => $request->district,
                    'municipality' => $request->municipality,
                    'ward' => $request->ward
                ]
            );
            $message  = "Home address created successfully.";
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
        $private_key = "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDPDEvlCvpeaHu2\ni79J5VDPZ2xN7VUHNKoAVCpgRlmUCFHRcu5PPMyddSTc5AIWjNk0tt+zGaFYz8mA\n1WPAb80xg66RvGdOjsD+lEg87vNNSidKaeusRxeg0XlXfqAqcQ0lRlidajQWz8iV\nGEdax2l7hoGu/AfAWMcXnoUBVoQnctsxvfkky9loguReise4+VYpT8Eb7l7i1/s3\nacwuAapaYH+HBhml6U+FFc/F8OvtP2qEynhzaLy5XaVvQPjY1b8yauoZOz7PxxfG\naYriTwPt40qrmdglc9VAe+rSVXZJBCvAfWdFO4jZuIdGqQ30JD/s/nWifHN2m8HC\ncuAqnl9TAgMBAAECggEAVA+6AJQkcpAZKlQypd3koVBsOHdTPkmze86wJcZqOB/o\nmBEBkEovCP62qLgp4N3ukehtilmtmFFEjnoUtdyugpvkFGdZyhtLYBD0A0lAnvCs\nYxn+BUQX48MocM2IWbLsk2WsNL0ZyOkzltT0o3ay7OP/YvwQaZehvYUumwx1O046\n5OvDQa6JzVdyvHArkcIB0bIZxB0B/lkXrjYN0fVbENzcPFSP46TKKTXzTajURYUX\nBAg2VhEYp/rDO9NNCILdVHGqp8eMQPiEJYQ14WS9cpVWJE6OCgOQcTqxcS3pOEMx\nETkXr7ymaFAXqJxonEF2pt6zYljBF0jNaIHzzeeggQKBgQD7YRDRUHSHQt0gMh3p\nn8rUTHa1pn7PvbVHzG7msHD6nm3ruu/+CVE+HXOVkL40HPSq6LKgBhSGmUYVR8uA\npLcM6k7UwIyxHhlnYrdo1HpvWKpTJ0T/ESjVeBWCHOwBNKoXOXm99i74fqIebPai\n1u0bBmnD0kl41q/w0gw2aISPBwKBgQDS2p5Mfc9vvjBPc1zvUimL/KzodHJs3IYW\nVIbdaJlV7ZfvdMujToCJV599cbu0cWAt5WAYyjyEZI/V0zEJGaS4hdlIXPiPJsen\nya1AU3ZhjwnHFl/BSk9c3p0ve7DwUzGBKmZAFW8t8PYVLY3sVthxosvcIua/DNCG\nHL3T8FOOVQKBgCWCV1MUUT75oCs0rzf0Cvzgp/n07QksgodDHu03OLR8vWQmUbcz\nRrchB+UyYt89zthNvpGYNqna5xU30ErSySmZMMgYLyYimqHNnhZ7VgWVUlz0CByT\nP+eScosmq6SGajbq8ZqByIJ1ytUFZ0vnDBwzOcbpcuMgDcK+ZOodcGIzAoGANwnv\n9kGE60M28xZG9QV6aNsc+1SiJb5uldecmKDcHaz2UwAmw8sTDEa+EA2nFJPfqjtM\nmUv4/goN7Z/CFgEGmU//Br+V9jAGP8sKGVdv+ElRIRG7DwZprvFBEIr2BdWBf5jk\nteeibNiQM7j/aejFeOwo0J6fotEigU9xUahS9z0CgYEAns+TOcLujIj/ifMcWUkh\nHuKx1KvAYCyPotvkj0ZRMfBOnAN5xJQxAobEFsKk1zFxvFCHxiV9tgfX870j9Qll\n2sCkxCVDs/UOcQQK+grus7Z+K6VVGoWV6N3fnIUobjwgNDTTT0yiaUdxDu7/2f/c\n8yQUWyl1jonE+IbsqDhUo2o=\n-----END PRIVATE KEY-----\n";
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

    public function roadBlockMessage()
    {
        $getRoadBlockMessage = RoadBlockMessage::where('type', 'rider')->latest()->first();
        if (!empty($getRoadBlockMessage) && $getRoadBlockMessage->status == 1) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $getRoadBlockMessage, 'statusCode' => 200], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'success', 'data' => null, 'statusCode' => 200], 200);
        }
    }



    public function globalConf()
    {

        $subscriptionTypes = SubscriptionPackage::where('hide', 0)->oldest()->get();

        $conf = DefaultConf::first();

        $customNotificationCount = 0;

        $user = auth()->guard('driver-api')->user();

        if ($user) {
            $customNotificationCount = $user->myNotifications()->count();
        }

        return response()->json([
            'data' => [
                'subscriptionPackage' => SubscriptionPackageResource::collection($subscriptionTypes),
                'appLatestVersion' => $conf ? $conf->partner_app_version : '',
                'appMinorUpdate' => $conf ? $conf->partner_app_minor : '',
                'appMajorUpdate' => $conf ? $conf->partner_app_major : '',
                'globalNotificationCount' => GlobalNotification::where('for', 'rider')
                    ->where('sent', 1)
                    ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
                    ->count(),
                'driverNotificationCount' => $customNotificationCount
            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }

    public function academy(Request $request)
    {
        $sliders = AcademySlider::where('fors', 'rider')->latest()->get();
        $contents = AcademyContent::where('fors', 'rider')->latest()->get();

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
