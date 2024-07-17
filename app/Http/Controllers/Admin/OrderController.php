<?php

namespace App\Http\Controllers\Admin;

use App\Custom\PushNotification;
use App\Delivery;
use App\DriverStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DeliveryResource;
use App\Http\Resources\Api\Ride\DeliveryResource as RideDeliveryResource;
use App\Http\Resources\Api\Ride\DriverResource;
use App\Http\Resources\Vendor\OrderResource;
use App\Http\Resources\Api\Ride\OrderResource as RideOrderResource;
use App\Order;
use App\Driver;
use App\Services\DeliveryService;
use App\Services\OrderService;
use Firebase\FirebaseLib;
use Illuminate\Http\Request;
use App\Http\Resources\Api\SendResource;
use App\Http\Resources\Api\PoolResource;
use App\Send;
use App\pool;

class OrderController extends CommonController
{
    protected $firebase;
    protected $path = 'deliveries/';
    protected $riderPath = 'riderDeliveries/';
    protected $riderTempPath = 'riderTempDeliveries/';
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    /** @var OrderService */
    private $orderService;

    /** @var DeliveryService */
    private $deliveryService;


    public function __construct(OrderService $orderService, DeliveryService $deliveryService)
    {
        parent::__construct();
        $this->orderService    =   $orderService;
        $this->deliveryService    =   $deliveryService;

        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
    }

    public function index(Request $request)
    {
        $gogosRider = Driver::all();
        if ($request->key == "") {
            // return OrderResource::collection($this->orderService->query()->latest()->where('status', '!=', 'DELIVERED')->where('status', '!=', 'CANCELLED')->paginate($this->paginationLimit))->additional(['rider'=>$gogosRider]);
            return OrderResource::collection($this->orderService->query()->latest()->paginate($this->paginationLimit))->additional(['rider' => $gogosRider]);
        } else {
            return OrderResource::collection($this->orderService->query()->where('status', $request->key)->latest()->paginate($this->paginationLimit))->additional(['rider' => $gogosRider]);
        }
    }

    public function destroy($orderId)
    {
        $order = $this->orderService->delete($orderId);

        return response('success');
    }

    public function update(Request $request, $orderId)
    {
        $order = $this->orderService->query()->where('id', $orderId)->first();
        $order->update(['status' => $request->status, 'date' => $request->deliveryDate]);
        return response('success');
    }

    public function status(Request $request)
    {
        $allOrder = $this->orderService->query()->get();
        $counts = $allOrder->groupBy('status')->map->count();
        $managedData = [];
        foreach ($counts as $key => $value) {
            $managedData[] = ["key" => $key, "value" => $value];
        }
        return response()->json(['status' => true, 'data' => $managedData]);
    }

    public function search(Request $request)
    {
        return OrderResource::collection($this->orderService->getAdminAdvancedData($request->name));
    }

    public function searchSendData(Request $request)
    {
        $sendData = Send::orderBy("id", 'desc')->paginate(10);
        return SendResource::collection($sendData);
    }

    public function searchPoolData(Request $request)
    {
        $poolData = pool::orderBy("id", 'desc')->paginate(10);
        return PoolResource::collection($poolData);
    }

    public function dispatchOrder(Request $request)
    {
        $order = $this->orderService->query()->where('id', $request->order)->first();
        $order->update(['accepted' => 1]);

        if ($order->deliveryRequest) {
            return response()->json(['status' => true, 'message' => 'Order has alredy been in marketplace and delivery status is ' . $order->deliveryRequest->status . '.']);
        } else {
            $data['order_id'] = $order->id;
            $data['from'] = $order->vendor->address;
            $data['to'] = $order->location;
            $data['from_lat'] = $order->vendor->lat;
            $data['from_long'] = $order->vendor->long;
            $data['to_lat'] = $order->lat;
            $data['to_long'] = $order->long;

            $delivery = $this->deliveryService->store($data);

            // Find Near by riders for delivery

            $this->sendNotification($order, $delivery);


            $order->update(['status' => 'IN MARKET PLACE']);

            return response()->json(['status' => true, 'message' => 'Current Order is in marketplace now..']);
        }
    }


    public function assignDelivery(Request $request)
    {
        if (isset($request->type) && $request->type == "send") {
            return response()->json(['status' => true, 'message' => 'Unable to perform right action from server']);
            // $sendItem = Send::where('id', $request->sendId)->first();
            // $sendItem->update(['status' => 2]); //Accepted by rider
            // if ($order->deliveryRequest) {
            // return response()->json(['status' => true, 'message' => 'Order has alredy been in marketplace and delivery status is ' . $order->deliveryRequest->status . '.']);
            // } else {
            // $data['order_id'] = $order->id;
            // $data['from'] = $order->vendor->address;
            // $data['to'] = $order->location;
            // $data['from_lat'] = $order->vendor->lat;
            // $data['from_long'] = $order->vendor->long;
            // $data['to_lat'] = $order->lat;
            // $data['to_long'] = $order->long;
            // $delivery = $this->deliveryService->store($data);
            // // Skip Find And Sending notification instead we will send  notificaiton only one rider
            // // $this->sendNotification($order, $delivery);
            // $driver = Driver::where('id',$request->rider_id)->first();
            // // $ridersToken[] = $driver->device->device_token ?? '';
            // // $ridersId[] = $driver->id;
            // // $this->riderFirebaseRTD($driver->id, $order, $delivery);
            // // $this->triggerNotification($ridersToken, $delivery, $order);
            // // $this->firebaseRTD($order, $delivery);
            // // $this->riderTempFirebaseRTD($ridersId, $delivery->id);
            // // $order->update(['status' => 'ASSIGNED TO RIDER']);
            // // return response()->json(['status' => true, 'message' => 'Order successfully assigned for delivery']);

            // //Create OTP
            // $verificationCode = randomNumericString(4);
            // $verificationCodeForUser = randomNumericString(4);
            // $delivery->update(['driver_id' => $driver->id,'user_otp'=>$verificationCodeForUser,'status' => 'ongoing', 'otp' => $verificationCode]);
            // $delivery->order->update(['status' => 'ASSIGNED TO RIDER']);
            // $driver->status()->update(['status' => 'ongoing']);
            // //Send notification to trip creator (user/customer)
            // $userDeviceToken = $delivery->order->user->device->device_token ?? '';
            // try {
            //     $this->triggerNotificationforUser([$userDeviceToken], $delivery, $driver, $verificationCode);
            //     $this->firebaseRTD($delivery->order, $delivery);
            //     $this->firebaseRiderTempRTD($delivery->id);
            // } catch (\Throwable $th) {
            //     throw $th;
            // }
            // return response()->json([
            //     'status' => true,
            //     'message' => 'Delivery has been assigned for you. Please be available at the Customer location ASAP.',
            //     'otp' => $verificationCode,
            //     'statusCode' => 200
            // ], 200);

            // }
        } else {
            $order = Order::where('id', $request->orderId)->first();
            $order->update(['accepted' => 1]);
            if ($order->deliveryRequest) {
                return response()->json(['status' => true, 'message' => 'Order has alredy been in marketplace and delivery status is ' . $order->deliveryRequest->status . '.']);
            } else {
                $data['order_id'] = $order->id;
                $data['from'] = $order->vendor->address;
                $data['to'] = $order->location;
                $data['from_lat'] = $order->vendor->lat;
                $data['from_long'] = $order->vendor->long;
                $data['to_lat'] = $order->lat;
                $data['to_long'] = $order->long;
                $delivery = $this->deliveryService->store($data);
                // Skip Find And Sending notification instead we will send  notificaiton only one rider
                // $this->sendNotification($order, $delivery);
                $driver = Driver::where('id', $request->rider_id)->first();
                // $ridersToken[] = $driver->device->device_token ?? '';
                // $ridersId[] = $driver->id;
                // $this->riderFirebaseRTD($driver->id, $order, $delivery);
                // $this->triggerNotification($ridersToken, $delivery, $order);
                // $this->firebaseRTD($order, $delivery);
                // $this->riderTempFirebaseRTD($ridersId, $delivery->id);
                // $order->update(['status' => 'ASSIGNED TO RIDER']);
                // return response()->json(['status' => true, 'message' => 'Order successfully assigned for delivery']);

                //Create OTP
                $verificationCode = randomNumericString(4);
                $verificationCodeForUser = randomNumericString(4);
                $delivery->update(['driver_id' => $driver->id, 'user_otp' => $verificationCodeForUser, 'status' => 'ongoing', 'otp' => $verificationCode]);
                $delivery->order->update(['status' => 'ASSIGNED TO RIDER']);
                $driver->status()->update(['status' => 'ongoing']);
                //Send notification to trip creator (user/customer)
                $userDeviceToken = $delivery->order->user->device->device_token ?? '';
                $driverDeviceToken = $delivery->driver->device->device_token ?? '';
                try {
                    $this->triggerNotificationforDriver([$driverDeviceToken], $delivery);
                    $this->triggerNotificationforUser([$userDeviceToken, $driverDeviceToken], $delivery, $driver, $verificationCode);
                    $this->firebaseRTD($delivery->order, $delivery);
                    $this->firebaseRiderTempRTD($delivery->id);
                } catch (\Throwable $th) {
                    throw $th;
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Delivery has been assigned for you. Please be available at the Customer location ASAP.',
                    'otp' => $verificationCode,
                    'statusCode' => 200
                ], 200);
            }
        }
    }

    public function firebaseRiderTempRTD($deliveryId)
    {
        $temps = json_decode($this->firebase->get($this->riderTempPath . $deliveryId));
        if (!empty($temps)) {
            foreach ($temps as $key => $riderId) {
                $this->firebase->delete($this->riderPath . $riderId);
            }
            $this->firebase->delete($this->riderTempPath . $deliveryId);
        }
    }

    private function triggerNotificationforDriver(array $token, $delivery)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'New delivery assigned to you',
                'delivery' => new DeliveryResource($delivery),
                'type' => 'delivery_assigned'
            ]
        );
        $notification->send();
    }

    private function triggerNotificationforUser(array $token, $delivery, Driver $driver, $otp)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Accepted by Rider',
                'delivery' => new DeliveryResource($delivery),
                'rider' => new DriverResource($driver),
                'type' => 'delivery_request',
                'otp' => $otp
            ]
        );
        $notification->send();
    }

    public function sendNotification($order, $delivery)
    {
        $nearByDistance = 10; // in km
        $availableNearByRiders = [];
        $ridersToken = [];
        $ridersId = [];
        // Collecting active riders
        $activeRiders = DriverStatus::where('status', 'active')->where(function ($query) {
            $query->where('interest', 'delivery');
            $query->orWhere('interest', 'Delivery');
        })->get();
        // $activeRiders = DriverStatus::where('interest', 'delivery')->orWhere('interest', 'Delivery')->get();

        foreach ($activeRiders as $key => $rider) {
            $distance =  number_format((float) getDistance($delivery->from_lat, $delivery->from_long, $rider->lat, $rider->long), 2, '.', '');

            if ($distance <= $nearByDistance) {
                $availableNearByRiders[] = $rider;
            }
        }

        foreach ($availableNearByRiders as $key => $riderStatus) {
            $ridersToken[] = $riderStatus->driver->device->device_token ?? '';
            $ridersId[] = $riderStatus->driver->id;

            $this->riderFirebaseRTD($riderStatus->driver->id, $order, $delivery);
        }

        // Firebase Push Notification to availableNearByRiders
        $this->triggerNotification($ridersToken, $delivery, $order);
        $this->firebaseRTD($order, $delivery);
        $this->riderTempFirebaseRTD($ridersId, $delivery->id);
    }

    public function triggerNotification(array $token, Delivery $delivery, Order $order)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Requested by Order',
                'order' => new RideOrderResource($order),
                'delivery' => new RideDeliveryResource($delivery),
                'type' => 'request'
            ]
        );
        $notification->send();
    }

    public function firebaseRTD(Order $order, Delivery $delivery)
    {
        $deliveryArray = [
            'order' => new RideOrderResource($order),
            'driver' => $delivery->driver ? new DriverResource($delivery->driver) : null,
            'delivery' => new RideDeliveryResource($delivery),
        ];

        $this->firebase->set($this->path . $delivery->id, $deliveryArray);
    }

    public function riderFirebaseRTD($driverId, Order $order, Delivery $delivery)
    {
        $tripArray = [
            'order' => new RideOrderResource($order),
            'delivery' => new RideDeliveryResource($delivery),
        ];

        $this->firebase->set($this->riderPath . $driverId, $tripArray);
    }

    public function riderTempFirebaseRTD($driverIds, $deliveryId)
    {
        $this->firebase->set($this->riderTempPath . $deliveryId, $driverIds);
    }
}
