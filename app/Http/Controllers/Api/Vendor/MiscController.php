<?php

namespace App\Http\Controllers\Api\Vendor;

// use App\Custom\Sms\Sparrow;
// use App\Custom\Sms\Twilio;
use App\Otp;
use App\GogoAd;
use App\Vendor;
use App\Category;
use App\DineInForm;
use App\AcademySlider;
use App\AcademyContent;
use App\RoadBlockMessage;
use App\Custom\Sms\Twilio;
use App\Custom\Sms\Sparrow;
use App\GlobalNotification;
use Illuminate\Http\Request;
use App\Custom\Sms\AakashSms;
use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\AdResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\DineinResource;
use App\Http\Resources\Api\ServiceResource;
use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Api\AcademySliderResource;
use App\Http\Resources\Api\AcademyContentResource;

class MiscController extends CommonController
{
    public function sendOtp(Request $request)
    {
        $user = Vendor::where('phone', $request->phone)->first();

        if ($user) {
            return response()->json(['details' => $user, 'status' => true, 'message' => 'Already Registered.', 'existedUser' => true, 'statusCode' => 200]);
        }

        $oldOtp = Otp::where('phone', '+' . $request->countryCode . $request->phone)->first();

        if ($oldOtp) {
            $oldOtp->delete();
        }

        $otp = randomNumericString(6);
        try {
            Otp::create(['otp' => $otp, 'phone' => '+' . $request->countryCode . $request->phone]);
            $message = 'Dear gogoPartner,';
            $message .= 'Welcome to Everyday Solution. Now,';
            $message .= ' Your OTP is ' . $otp . '.';
            $message .= ' It is valid for 15 minutes.';
            $message .= ' Thank You! ';
            $message .= ' Best Regards - Team gogo20 ' . $request->appId ?? '';

            if ('+' . $request->countryCode == "+977") {
                $sms = new Sparrow($request->phone, $message);
                $response = $sms->sendMessage();

                if ($response == 200) {
                    return response()->json(['status' => true, 'message' => 'success', 'existedUser' => false, 'statusCode' => 200], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'SMS Error', 'statusCode' => 422], 422);
                }
                return response()->json(['status' => false, 'message' => 'SMS Error', 'statusCode' => 422], 422);
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
        $user = Vendor::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User Not Found.', 'existedUser' => false, 'statusCode' => 200], 200);
        }

        if ($user->verified == 0) {
            return response()->json(['status' => false, 'message' => 'User Not Verified. Please contact support.', 'existedUser' => false, 'statusCode' => 200], 200);
        }

        $oldOtp = Otp::where('phone', '+' . $request->countryCode . $request->phone)->first();

        if ($oldOtp) {
            $oldOtp->delete();
        }

        $otp = randomNumericString(6);
        try {
            Otp::create(['otp' => $otp, 'phone' => '+' . $request->countryCode . $request->phone]);

            $message = 'Dear ' . $user->name . ',';
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

        $vendor = auth()->guard('vendor-api')->user();


        if (Hash::check($request->currentPassword, $vendor->password)) {
            if ($vendor->update(['password' => bcrypt($request->newPassword)])) {
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
        $user = Vendor::where('phone', $request->phone)->Where('country_code', '+' . $request->countryCode)->first();

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

    public function ads()
    {
        return AdResource::collection(GogoAd::where('type', 'vendor')->where('hide', 0)->get())->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }

    public function roadBlockMessage()
    {
        $getRoadBlockMessage = RoadBlockMessage::where('type', 'vendor')->latest()->first();
        if (!empty($getRoadBlockMessage) && $getRoadBlockMessage->status == 1) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $getRoadBlockMessage, 'statusCode' => 200], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'success', 'data' => null, 'statusCode' => 200], 200);
        }
    }

    public function defaultData()
    {
        $commission = 0;
        $customNotificationCount = 0;
        $services = Category::where("enabled", 1)->where('ondemand', 1)->orderBy('order')->get();

        $user = auth()->guard('vendor-api')->user();

        if ($user) {
            $commission = (($user->products()->sum('price') - $user->products()->sum('price_1')) / $user->products()->sum('price')) * 100;

            $customNotificationCount = $user->myNotifications()->count();
        }

        return response()->json([
            'data' => [
                'serviceList' => ServiceResource::collection($services),
                'commission' => round($commission),
                'globalNotificationCount' => GlobalNotification::where('for', 'vendor')
                    ->where('sent', 1)
                    ->whereDate('updated_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))
                    ->count(),
                'vendorNotificationCount' => $customNotificationCount
            ],
            'status' => true,
            'message' => 'success',
            'statusCode' => 200
        ], 200);
    }

    public function academy(Request $request)
    {
        $sliders = AcademySlider::where('fors', 'vendor')->latest()->get();
        $contents = AcademyContent::where('fors', 'vendor')->latest()->get();

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

    public function dineInList(Request $request)
    {
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if ($request->status) {
            $forms = $vendor->dineInForms()->where('status', $request->status)->latest()->paginate($this->paginationLimit)->appends($request->query());
        } else {
            $forms = $vendor->dineInForms()->latest()->paginate($this->paginationLimit)->appends($request->query());
        }
        return DineinResource::collection($forms)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function dineInStatus(Request $request)
    {
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'dineInId' => 'required',
            'status' => 'required|in:confirmed,cancelled'
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $form = DineInForm::where('vendor_id', $vendor->id)
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'confirmed')
            ->where('status', '!=', 'cancelled')
            ->where('id', $request->dineInId)->first();

        if (!$form) {
            return failureResponse("Dinein request form not found.", 404, 404);
        }

        try {
            $form->update(['status' => $request->status]);

            try {
                $notification = new PushNotification(
                    $form->user->devices->pluck('device_token')->toArray(),
                    [
                        'title' => 'Dinein Form Status',
                        'message' => 'Your dinein form request is now ' . $request->status . '.',
                        'type' => 'dinein',
                    ]
                );
                $notification->send();
            } catch (\Throwable $th) {
                //throw $th;
            }

            $form->user->myNotifications()->create(['title' => "Dinein Form Status", 'message' => 'Your dinein form request is now ' . $request->status . '.', 'type' => 'dinein', 'task' => $form->id]);

            return successResponse("Operation success.", 200, 200);
        } catch (\Throwable $th) {
            return failureResponse("We're unable to process this operation.", 422, 422);
        }
    }
}
