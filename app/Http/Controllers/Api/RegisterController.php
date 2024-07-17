<?php

namespace App\Http\Controllers\Api;

use App\Custom\SocialLogins\Factories\SocialProviderFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Admin\UserResource;
use App\Mail\VerifyEmail;
use App\Otp;
use App\Referral;
use App\Services\UserService;
use App\User;
use App\UserDevice;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function customAlgorithm()
    {
        $requestDetails = array(
            "user_id" => 1,
            "gender" => "male",
            "no_smoking_preference" => 0,
            "child_seat_preference" => 0,
            "handicap_support_preference" => 0,
            "gender_base_preference" => 0,
            "favourite_rider_preference" => 0
        );
        $riderDetails = [];
        for ($i = 1; $i < 20; $i++) {
            $riderDetails[] = array(
                "user_id" => $i,
                "gender" => rand(0, 1) == 0 ? "female" : "male",
                "no_smoking" => rand(0, 1),
                "child_seat_support" => rand(0, 1),
                "gender_based_support" => rand(0, 1),
                "is_favourite" => rand(0, 1),
                "rating" => rand(0, 5),
                "waiting_time_in_minute" => rand(20, 18),
                "total_ride" => rand(1, 200),
                "today_total_ride" => rand(1, 10),
                "average_cancle_rate" => rand(0, 10),
                "calculated_distance" => rand(20, 28)
            );
        }
        /* 
        Preference level is categorized by decimal level i.e. 0 to 10
        0 means 0% preferance probability       0 -> low
        10 means 100% preference probabiliy     10-> high
        */

        /* 
        USER PREFERENCE   // 1 means 100%,   0 means => 0%
        no_smoking
        child_seat
        handicap_support
        gender_base
        favourite_rider

        PREFERENCE BASIS
        gender: low value preference means low gender differenciation. i.e 0 means ignoring gender preference
        fifs: First In First Serve.
        rating: 1 means low rating low chance (10%), 9 means high rating high chance(90%)
        LTHP: Low Trip High Probability. 
        NDHP: Near Distance High Probability.
        HCLP: High cancel low probability.
         */

        $systemPreferenceOrderAndValue = array(
            "fifs" => 8,
            "LTHP" => 4,
            "NDHP" => 3,
            "HCLP" => 6,
            "gender" => 10,
            "rating" => 10,
        );
        $totalNumberOfRider = count($riderDetails);
        $riderBasedOnUserPreference = [];
        $indexCount = 7;
        $indexValue = 0;
        if ($totalNumberOfRider > 1) {
            // looping rider details and adding value based on requested user preference
            foreach ($riderDetails as $eachRider) {
                $indexCount = 7;
                $indexValue = 0;
                $riderBasedOnUserPreference[$eachRider['user_id']] = array(
                    'riderId' => $eachRider['user_id'],
                    'userBasedPreference' => 0
                );
                foreach ($requestDetails as $userPreference => $preferenceKey) {
                    switch ($userPreference) {
                        case 'no_smoking_preference':
                            if ($requestDetails[$userPreference] == 1 && $eachRider['no_smoking'] == 1) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + 20;
                            }
                            break;
                        case 'child_seat_preference':
                            if ($requestDetails[$userPreference] == 1 && $eachRider['child_seat_support'] == 1) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + 20;
                            }
                            break;
                        case 'handicap_support_preference':
                            if ($requestDetails[$userPreference] == 1 && $eachRider['handicap_support'] == 1) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + 20;
                            }
                            break;
                        case 'gender_base_preference':
                            if ($requestDetails[$userPreference] == 1 && $eachRider['gender'] == $requestDetails['gender']) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + 20;
                            }
                            break;
                        case 'favourite_rider_preference':
                            if ($requestDetails[$userPreference] == 1 && $eachRider['is_favourite'] == 1) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + 20;
                            }
                            break;
                    }
                }

                foreach ($systemPreferenceOrderAndValue as $keyName => $preferenceValue) {
                    $totalAddValue = 0;
                    $indexCount--;
                    $indexValue = $indexCount * 10;
                    $totalAddValue = $indexValue + $preferenceValue;
                    switch ($keyName) {
                        case 'gender':
                            if ($requestDetails['gender'] == $eachRider['gender']) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue;
                            }
                            break;
                        case 'fifs':
                            //Add preference value based on first serve method
                            $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + ($eachRider['user_id']['waiting_time_in_minute'] / 5) + $totalAddValue;
                            break;
                        case 'rating':
                            $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue + ($eachRider['user_id']['rating'] * 2);
                            break;
                        case 'NDHP':
                            $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue - ($eachRider['user_id']['calculated_distance'] / 2);
                            break;
                        case 'LTHP':
                            if ($eachRider['user_id']['today_total_ride'] < 1) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue + 5;
                            } else {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue + ($totalAddValue / $eachRider['user_id']['today_total_ride']);
                            }
                            break;
                        case 'HCLP':
                            if ($eachRider['user_id']['average_cancle_rate'] < 1) {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue + 5;
                            } else {
                                $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] = $riderBasedOnUserPreference[$eachRider['user_id']]['userBasedPreference'] + $totalAddValue + ($totalAddValue / $eachRider['user_id']['average_cancle_rate']);
                            }
                            break;
                    }
                }
            }
            echo "<h1>Rider Details:</h1><pre>";
            print_r($riderDetails);
            echo "</pre>";

            echo "<h1>Request Details:</h1><pre>";
            print_r($requestDetails);
            echo "</pre>";

            dd($riderBasedOnUserPreference);
        }
    }

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     *
     * @return UserResource
     * @throws \Exception
     */
    public function register(RegisterRequest $request)
    {
        // $social = SocialProviderFactory::make($request->input('from'), $request->input('token'), $request->input('email'));
        // $social->verify();

        if ($request->from == 'normal') {
            $checkOtp = $this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone);
            if (!$checkOtp) {
                return failureResponse("otp doesnot match", 404, 404);
            }
        }

        $user = $this->saveUser($request->all());

        if ($request->deviceToken && $request->deviceType) {
            $this->saveDevice($user, $request);
        }
        $token = auth()->guard('api')->login($user);
        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        try {
            Mail::to($user)->send(new VerifyEmail($user->email_token));
        } catch (\Throwable $th) {
            //throw $th;
        }

        //Check and do the Referral work
        $this->isValidReferCode($request->referCode, $user);

        return (new UserResource($user, $token))->additional(['status' => true, 'message' => "Registration successful.", 'statusCode' => 200], 200);
    }

    /**
     * Save or update user's data
     *
     * @param array $all
     *
     * @return User|bool|\Illuminate\Database\Eloquent\Model
     */
    private function saveUser(array $all)
    {
        // $refer = substr($all['firstName'] . $all['lastName'], 0, 3);
        $lastRecordId = User::orderBy("id",'desc')->limit(1)->first()->id;
        $refer = "gogo".$lastRecordId;
        $data = [
            'firstName'       => $all['firstName'],
            'lastName' => $all['lastName'],
            // 'email'      => $all['email'] ?? '',
            'password'   => $all['password'] ?? '',
            'countryCode'      => "+" . $all['countryCode'] ?? '',
            'phone'      => $all['phone'] ?? '',
            // 'phone1'      => $all['optPhone'] ?? '',
            'socialFrom' => $all['from'],
            'emailToken' => $all['from'] == 'normal' ? str_random(11) : '',
            'verified' => $all['from'] != 'normal' ? 1 : 0,
            // 'dob' => $all['dob'] ?? '',
            'gender' => $all['gender'] ?? '',
            // 'lat' => $all['lat'] ?? '',
            // 'long' => $all['long'] ?? '',
            // 'address' => $all['address'] ?? '',
            // 'office' => $all['office'] ?? '',
            'heardFrom' => $all['heardFrom'] ?? '',
            // 'image'  => $all['image'] ?? '',
            'phone_verified' => $all['from'] == 'normal' ? 1 : 0,
            'refer_code' => strtoupper($refer . randomAlphaNumericString(4))

        ];

        if ($data['socialFrom'] == 'normal') {
            $user = $this->userService->findBy('phone', $data['phone']);
        } else {
            $user = $this->userService->findBy('email', $data['email']);
        }

        if ($user) {
            $user = $this->userService->updateByModel($user, $data);
        } else {
            $user = $this->userService->store($data);
            // if ($data['image']) {
            //     $this->userService->update($user->id, $data['image']);
            // }
        }
        return $user;
    }

    public function verifyMyOtp($otp, $phone)
    {
        $isExist = Otp::where('otp', $otp)->Where('phone', $phone)->exists();
        if ($isExist) {
            return true;
        }
        return false;
    }

    private function saveDevice($user, $request)
    {
        if (!($user->device) && $request->deviceType && $request->deviceToken) {
            if (UserDevice::where('device_token', $request->deviceToken)->count() == 0) {
                $user->device()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
            }
        } elseif (($user->device) && $request->deviceType && $request->deviceToken) {
            $userDevice = $user->device;
            if ($userDevice['device_token'] != $request->deviceToken) {
                $user->device()->update(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
            }
        }
    }

    public function isValidReferCode($code, $user)
    {
        try {
            if(strlen($code) < 4){
                return false;
            }else{
                $refUser = User::where('refer_code', strtoupper($code))->first();
                if (!empty($refUser)) {
                    $usedBy = Referral::create(['user_id' => $refUser->id, 'used_by' => $user->id]);
                    $user->update(['used_code' => $code, 'reward_point' => $user->reward_point + 50]);
                    $refUser->update(['reward_point' => $user->reward_point + 25]);
                    return true;
                }
                return false;
            }
        } catch (\Throwable $th) {
            \Log::info("Error Message referral:"+$th->getMessage());
        }
    }
}
