<?php

namespace App\Http\Controllers\Api;

use App\Otp;
use App\User;
use App\Voucher;
use App\Referral;
use App\UserDevice;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Custom\Helper\EmailValidator;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Admin\UserResource;
use App\Custom\SocialLogins\Factories\SocialProviderFactory;
use App\DefaultConf;

class RegisterController extends CommonController
{
    /**
     * @var UserService
     */
    private $userService;

    protected $conf = [];


    public function __construct(UserService $userService)
    {
        parent::__construct();

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
        $firstDownload = false;

        if ($request->email) {
            $response = new EmailValidator($request->email);

            if (!$response->validate()) {
                return failureResponse("Invalid email.", 422, 422);
            }
        }

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

        if ($request->deviceId) {
            $isThere = User::where('device_id', $request->deviceId)->first();
            if (!$isThere &&  $this->conf['first_download_reward'] > 0) {
                $firstDownload = true;
                $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $this->conf['first_download_reward'], 'from' => 'First Download Point']);
            }
        }

        if ($user->district) {
            $user->myAddress()->create(['district' => $request->district, 'municipality' => $request->municipality, 'ward' => $request->ward, 'organization' => $request->organization]);
        }


        $token = auth()->guard('api')->login($user);

        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();

        try {
            if ($request->email) {
                // Mail::to($user)->send(new VerifyEmail($user->email_token));
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        //Check and do the Referral work

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
            $this->isValidReferCode($request->referCode, $user);
        }

        //Last Login Time
        auth()->guard('api')->user()->update(
            [
                'last_login_at' => now(),
                'access_token' => $token,
                'device_id' => $firstDownload == true ? $request->deviceId : null,
                'reward_point' => $firstDownload == true ? $this->conf['first_download_reward'] : $user->reward_point
            ]
        );


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
        try {
            $lastRecordId = User::orderBy("id", 'desc')->limit(1)->first()->id;
        } catch (\Throwable $th) {
            $lastRecordId = 0;
        }
        $refer = "gogo" . $lastRecordId;
        $code = strtoupper($refer . randomAlphaNumericString(5));

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
            'lat' => $all['lat'] ?? '',
            'long' => $all['long'] ?? '',
            'address' => $all['address'] ?? '',
            'office' => $all['officeAddress'] ?? '',
            'heardFrom' => $all['heardFrom'] ?? '',
            'registered_from' => $all['web'] ?? 'app',
            // 'image'  => $all['image'] ?? '',
            'phone_verified' => $all['from'] == 'normal' ? 1 : 0,
            'refer_code' => substr($code, 0, 10)

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
        if ($request->deviceType && $request->deviceToken) {
            $user->devices()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
        }
    }

    public function isValidReferCode($code, $user)
    {
        try {
            if (strlen($code) != 10) {
                return false;
            } else {
                $refUser = User::where('refer_code', strtoupper($code))->first();
                if (!empty($refUser)) {
                    $defaultConf = DefaultConf::firstOrFail();

                    // $userReferLimit = $this->conf['user_refer_limit'];
                    $userReferLimit = $defaultConf->user_refer_limit;

                    if ($refUser->whoUsedMyCode()->count() < $userReferLimit) {
                        $usedBy = Referral::create(['user_id' => $refUser->id, 'used_by' => $user->id]);
                        if ($defaultConf->referral_user_point > 0) {
                            $refUser->update(['reward_point' => ($refUser->reward_point + $defaultConf->referral_user_point)]);
                            $refUser->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $defaultConf->referral_user_point, 'from' => 'Referral Point']);

                            $refUser->myNotifications()->create([
                                'title' => "Referral Point Received",
                                'message' => 'Congratulations! You have received ' . $defaultConf->referral_user_point . ' points on successful installation of gogo20 referred by you.',
                                'type' => 'point',
                                'task' => $refUser->id
                            ]);
                        }

                        if ($defaultConf->referred_user_point > 0) {
                            $user->update(['used_code' => $code, 'reward_point' => $defaultConf->referred_user_point]);
                            $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $defaultConf->referred_user_point, 'from' => 'Referred Point']);

                            $user->myNotifications()->create([
                                'title' => "Referred Point Received",
                                'message' => 'Congratulations! You have received ' . $defaultConf->referred_user_point . ' points on successful installation of gogo20.',
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
}
