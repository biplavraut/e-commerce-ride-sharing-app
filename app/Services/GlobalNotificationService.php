<?php

namespace App\Services;

use App\Driver;
use App\UserDevice;
use App\DriverDevice;
use App\DriverStatus;
use App\GlobalNotification;
use App\Custom\Sms\AakashSms;

class GlobalNotificationService extends ModelService
{
    const MODEL = GlobalNotification::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function androidNotification($request)
    {
        $request['message'] = strip_tags($request->message);

        if ($request->geo == 1) {
            $deviceTokens = $this->getTokenForGeo($request->lat, $request->long, $request->radius, $$request->for, $request);
            $this->sendAndroidNotification($deviceTokens, $request->title, $request->message, $request->image, $request->id);
        } else {
            if ($request->for == "rider" || $request->for == "active-rider" || $request->for == "inactive-rider") {
                $devices = $this->otherRiderToken($request->for, $request);
            } else {
                $phones = [];
                $devices = UserDevice::where('device_type', 'android')->get();
                foreach ($devices as $key => $device) {
                    $phones[] = $device->user->phone;
                }
                $this->sendSMS($phones, $request);
            }

            foreach ($devices as $row) {
                $this->sendAndroidNotification($row->device_token, $request->title, $request->message, $request->image, $request->id);
            }
        }
        return true;
    }

    public function iosNotification($request)
    {
        $request['message'] = strip_tags($request->message);

        if ($request->for == "rider") {
            $devices = DriverDevice::where('device_type', 'ios')->get();
        } else {
            $devices = UserDevice::where('device_type', 'ios')->get();
        }
        foreach ($devices as $row) {
            $this->sendiOSNotification($row->device_token, $request->title, $request->message, $request->image);
        }
        return true;
    }

    private function sendAndroidNotification($token, $titles, $message, $image, $id)
    {
        $registrationIds = $token;
        $androidKey = env('ANDROID_SERVER_KEY');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $msg = [
            'id' => $id,
            'message' => $message,
            'title' => $titles,
            'image' => $image,
            'vibrate' => 1,
            'sound' => 1,
            'type' => 'global'
        ];
        $fields = [
            'registration_ids' => [$registrationIds],
            'data' => $msg,
        ];

        $headers = array(
            'Authorization:key = ' . $androidKey,
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
    }

    private function sendiOSNotification($deviceId, $titles, $message, $img)
    {
        $iosKey = env('IOS_SERVER_KEY');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $ch = curl_init($url);
        //The device token.
        $token = $deviceId; //token here
        //Title of the Notification.
        $title = $titles;
        //Body of the Notification.
        $body = $message;

        $image = $img;
        //Creating the notification array.
        $notification = ['title' => $title, 'text' => $body, 'image' => $image, 'sound' => 'default',  'type' => 'global', "content_available" => true];
        //This array contains, the token and the notification. The 'to' attribute stores the token.
        $arrayToSend = ['to' => $token, 'notification' => $notification, 'priority' => 'high'];
        //Generating JSON encoded string form the above array.
        $json = json_encode($arrayToSend);
        //Setup headers:
        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key= ' . $iosKey; // key here
        //Setup curl, add headers and post parameters.
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Send the request
        $response = curl_exec($ch);
        //Close request
        curl_close($ch);
    }

    public function getTokenForGeo($lat, $long, $radius, $for, $request): array
    {
        $availableNearByRiders = [];
        $ridersDeviceTokens = [];
        $ridersPhone = [];

        if ($for == "rider") {
            $riders = DriverStatus::get();
        } else if ($for == "active-rider") {
            $riders = DriverStatus::where('status', '!=', 'inactive')->get();
        } else if ($for == "inactive-rider") {
            $riders = DriverStatus::where('status', 'inactive')->get();
        }

        foreach ($riders as $key => $rider) {
            $distance =  number_format((float) getDistance($lat, $long, $rider->lat, $rider->long), 2, '.', '');

            if ($distance <= $radius) {
                $availableNearByRiders[] = $rider;
            }
        }

        foreach ($availableNearByRiders as $key => $riderStatus) {
            $ridersDeviceTokens[] = $riderStatus->driver->device->device_token;
            $ridersPhone[] = $riderStatus->driver->phone;
        }
        $this->sendSMS($ridersPhone, $request);

        return $ridersDeviceTokens;
    }

    public function otherRiderToken($for, $request): array
    {
        $ridersDevices = [];
        $ridersPhone = [];

        if ($for == "rider") {
            $riders = DriverStatus::get();
        } else if ($for == "active-rider") {
            $riders = DriverStatus::where('status', '!=', 'inactive')->get();
        } else if ($for == "inactive-rider") {
            $riders = DriverStatus::where('status', 'inactive')->get();
        } else if ($for == "unverified-rider") {
            $riders = Driver::where('verified', 0)->get();
            foreach ($riders as $key => $rider) {
                $ridersDeviceTokens[] = $rider->device;
                $ridersPhone[] = $rider->phone;
            }
            $this->sendSMS($ridersPhone, $request);

            return $ridersDevices;
        } else if ($for == "verified-rider") {
            $riders = Driver::where('verified', 1)->get();
            foreach ($riders as $key => $rider) {
                $ridersDeviceTokens[] = $rider->device;
                $ridersPhone[] = $rider->phone;
            }
            $this->sendSMS($ridersPhone, $request);

            return $ridersDevices;
        }

        foreach ($riders as $key => $riderStatus) {
            $ridersDeviceTokens[] = $riderStatus->driver->device;
            $ridersPhone[] = $riderStatus->driver->phone;
        }
        $this->sendSMS($ridersPhone, $request);


        return $ridersDevices;
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

        $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $numberFormat, $request->message);
        $response = $sms->sendMessage();

        return true;
    }
}
