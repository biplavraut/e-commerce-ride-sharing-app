<?php

namespace App\Services;

use App\Custom\PushNotification;
use App\User;
use App\Driver;
use App\UserDevice;
use App\DriverDevice;
use App\DriverStatus;
use App\GlobalNotification;
use App\Custom\Sms\AakashSms;
use App\Custom\Sms\Sparrow;
use Illuminate\Support\Facades\Log;

class GlobalNotificationService extends ModelService
{
    const MODEL = GlobalNotification::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function send($request)
    {
        $deviceTokens = $this->getTokenForGeo($request->lat, $request->long, $request->radius, $request->for, $request);
        //$deviceTokens = UserDevice::pluck('device_token')->toArray();
        //dd($deviceTokens);

        $payload = [
            'id' => $request->id,
            'message' => $request->message,
            'title' => $request->title,
            'image' => 'https://gogo20.com/storage/images/global-notification/images/tihar-notificaiton-20211103172051-ZWleJ8.jpg',
            'vibrate' => 1,
            'sound' => 1,
            'type' => 'global'
        ];


        if (count($deviceTokens) > 1000) {
            $responseCount = 0;
            foreach ($this->getChunkToken($deviceTokens) as $key => $batchToken) {
                $notification = new PushNotification($batchToken, $payload);
                $response = json_decode($notification->send(), true);

                Log::channel("notification")->info('--------------cHUNK----------------');
                Log::channel("notification")->info('cHUNK ' . $key . json_encode($response));

                try {
                    $response['success'] > 0 ? $responseCount++ : $responseCount;
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            return $responseCount > 0 ? true : false;
        } else {
            $notification = new PushNotification($deviceTokens, $payload);
            $response = json_decode($notification->send(), true);
            Log::channel("notification")->info('---------------------------------');
            Log::channel("notification")->info(json_encode($response));

            try {
                return $response['success'] > 0 ? true : false;
            } catch (\Throwable $th) {
                return false;
            }
        }
    }


    public function getTokenForGeo($lat, $long, $radius, $for, $request): array
    {
        $availableNearByRiders = [];
        $ridersDeviceTokens = [];
        $ridersPhone = [];
        $riderStatues = [];

        if ($for == "rider") {
            $availableNearByRiders = Driver::get();
            Log::channel('notification')->info("Riders");
        } elseif ($for == "active-rider") {
            $riderStatues = DriverStatus::where('status', '!=', 'inactive')->get();
        } elseif ($for == "inactive-rider") {
            $riderStatues = DriverStatus::where('status', 'inactive')->get();
        } elseif ($for == "verified-rider") {
            $availableNearByRiders = Driver::where('verified', 1)->get();
        } elseif ($for == "male-rider") {
            $availableNearByRiders = Driver::where('gender', 'male')->get();
        } elseif ($for == "female-rider") {
            $availableNearByRiders = Driver::where('gender', 'female')->get();
        } elseif ($for == "unverified-rider") {
            $availableNearByRiders = Driver::where('verified', 0)->get();
        } elseif ($for == "blocked-rider") {
            $availableNearByRiders = Driver::where('is_blocked', 1)->get();
        } elseif ($for == "blacklisted-rider") {
            $availableNearByRiders = Driver::where('blacklisted', '>=', 1)->get();
        } elseif ($for == "associated-rider") {
            $availableNearByRiders = Driver::where('is_associated_rider', 1)->get();
        } elseif ($for == "incomplete-rider") {
            $availableNearByRiders = Driver::whereHas('vehicleDetail', function ($query) {
                $query->where('status', 0);
            })->get();
        } elseif ($for == "end-user") {
            try {
                $users = User::pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }

        } elseif ($for == "active-user") {
            try {
                $users = User::where('last_login_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->orderBy('last_login_at', 'desc')->pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::where('last_login_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->orderBy('last_login_at', 'desc')->get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }
        } elseif ($for == "passive-user") {
            try {
                $users = User::where('last_login_at', '<=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->orderBy('last_login_at', 'desc')->pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::where('last_login_at', '<=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->orderBy('last_login_at', 'desc')->get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }
        } elseif ($for == "blocked-user") {
            try {
                $users = User::where('blocked', 1)->pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::where('blocked', 1)->get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }
        } elseif ($for == "elite-user") {
            try {
                $users = User::where('elite', 1)->pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::where('elite', 1)->get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }
        } elseif ($for == "male-user") {
            try {
                $users = User::where('gender', 'male')->pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::where('gender', 'male')->get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }
        } elseif ($for == "female-user") {
            try {
                $users = User::where('gender', 'female')->pluck('id');
                $ridersPhone = User::pluck('phone')->toArray();
                $ridersDeviceTokens = UserDevice::whereIn('user_id', $users)->pluck('device_token')->toArray();
            } catch (\Throwable $th) {
                //throw $th;
            }
            // $users = User::where('gender', 'female')->get();
            // foreach ($users as $user) {
            //     $ridersPhone[] = $user->phone;
            //     try {
            //         $ridersDeviceTokens = array_merge($ridersDeviceTokens, $user->devices->pluck('device_token')->toArray());
            //     } catch (\Throwable $th) {
            //         //throw $th;
            //     }
            // }
        }

        if (count($availableNearByRiders) > 0) {
            \Log::channel('notification')->info("Riders Available");

            foreach ($availableNearByRiders as $rider) {
                if ($request->geo == 1) {
                    $distance =  number_format((float) getDistance($lat, $long, $rider->lat, $rider->long), 2, '.', '');
                    if ($distance <= $radius) {
                        try {
                            $ridersDeviceTokens = array_merge($ridersDeviceTokens, $rider->devices->pluck('device_token')->toArray());
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        $ridersPhone[] = $rider->phone;
                    }
                } else {
                    try {
                        $ridersDeviceTokens = array_merge($ridersDeviceTokens, $rider->devices->pluck('device_token')->toArray());
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $ridersPhone[] = $rider->phone;
                }
            }
            \Log::channel('notification')->info("Riders Device Token" . count($ridersDeviceTokens));
            \Log::channel('notification')->info("Riders Phone" . count($ridersPhone));
        }

        if (count($riderStatues) > 0) {
            foreach ($riderStatues as $status) {
                if ($request->geo == 1) {
                    $distance =  number_format((float) getDistance($lat, $long, $status->lat, $status->long), 2, '.', '');
                    if ($distance <= $radius) {
                        try {
                            $ridersDeviceTokens = array_merge($ridersDeviceTokens, $rider->devices->pluck('device_token')->toArray());
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                        $ridersPhone[] = $status->driver->phone;
                    }
                } else {
                    try {
                        $ridersDeviceTokens = array_merge($ridersDeviceTokens, $rider->devices->pluck('device_token')->toArray());
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $ridersPhone[] = $status->driver->phone;
                }
            }
        }


        if ($request->sms == 1 && count($ridersPhone) > 0) {
            $this->sendSMS($ridersPhone, $request);
        }

        return $ridersDeviceTokens;
    }


    public function sendSMS($numbers, $request)
    {
        $numberFormat = null;
        if (!$request->sms) {
            return;
        }

        foreach ($numbers as $key => $number) {
            $numberFormat .= $number . ",";
        }

        $message = $request->title . ", \n";
        $message .= $request->message;

        try {
            $sms = new Sparrow($numberFormat, $message);
            $response = $sms->sendMessage();

            if ($response != 200) {
                $sms = new AakashSms(null, $numberFormat, $message);
                $response = $sms->sendMessage();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return true;
    }

    public function getChunkToken($data)
    {
        return array_chunk($data, 500);
    }
}
