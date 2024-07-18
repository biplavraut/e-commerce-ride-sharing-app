<?php

namespace App\Http\Controllers\Admin;

use App\Custom\Order\ProcessCancellation;
use App\Custom\Order\ProcessDelivery;
use App\pool;
use App\Send;
use App\Order;
use App\Driver;
use App\Delivery;
use App\DriverStatus;
use Firebase\FirebaseLib;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Custom\PushNotification;
use App\Services\DeliveryService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PoolResource;
use App\Http\Resources\Api\SendResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Vendor\OrderResource;
use App\Http\Resources\Admin\DeliveryResource;
use App\Http\Resources\Admin\OrderDetailResource;
use App\Http\Resources\Api\Ride\DriverResource;
use App\Http\Resources\Api\Ride\OrderResource as RideOrderResource;
use App\Http\Resources\Api\Ride\DeliveryResource as RideDeliveryResource;
use App\User;
use Carbon\Carbon;

class OrderController extends CommonController
{
    protected $firebase;
    protected $firebase1;
    // protected $path = 'test/deliveries/';
    // protected $riderPath = 'test/riderDeliveries/';
    // protected $riderTempPath = 'test/riderTempDeliveries/';
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
        $this->firebase1 = new FirebaseLib(env('FIREBASE_DATABASEURL'), env('FIRBASE_ADMIN_SEC'));
    }

    public function index(Request $request)
    {
        $this->firebase1->delete(env('ORDER_PATH', 'orders/'));
        $pendingOrders = $this->orderService->query()->where('status', 'PENDING')->count();
        $this->firebase1->set(env('PENDING_PATH', 'pending/'), $pendingOrders);

        // $gogosRider = Driver::where('verified', 1)->with('junction')->get();

        if ($request->key == "") {
            return OrderResource::collection($this->orderService->query()->latest()->paginate($this->paginationLimit));
        } else {
            $orders = $this->orderService->query()->where('status', $request->key)->latest()->paginate($this->paginationLimit);
            $orders->appends(['key' => $request->key])->links();
            return OrderResource::collection($orders)->additional(['meta' => [
                'key' => $request->key,
            ]]);
        }
    }

    public function destroy($orderId, Request $request)
    {
        $order = $this->orderService->query()->where('id', $orderId)->first();
        $user = $order->user;
        if ($order->status === 'CANCELLED' || $order->status === 'DELIVERED') {
            return response('Order already cancelled or delivered');
        } else {
            if ($order->created_at < new Carbon('2021-12-11 00:00:00')) {
                if ($order->payment_mode == "gogoPoint") {
                    $user->update(['reward_point' => $order->user->reward_point + round($order->total)]);
                    $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => $order->total, 'from' => 'Order Cancelled, Reward Refund']);
                }
                // Cancel the order
                $order->shipping_fee = 0; //Shipping charge was applied in this product
                $order->total = $order->subtotal + $order->shipping_fee;
                $order->status = 'CANCELLED';
                $order->reason = $request->reason . "\r\n" . $order->reason;
                $order->save();
                // Finish Cancellation
                $notification = new PushNotification(
                    $user->devices->pluck('device_token')->toArray(),
                    [
                        'title' => "Order Cancelled",
                        'message' => 'Your Order ' . $order->orderNo() . '  has been cancelled. Reason: ' . $request->reason,
                        'type' => 'order',
                    ]
                );
                $notification->send();
                $user->myNotifications()->create(['title' => 'Order cancelled', 'message' => 'Your Order ' . $order->orderNo() . '  has been cancelled. Reason: ' . $request->reason, 'type' => 'order', 'task' => $order->orderNo()]);
                return response('success');
            }
            $processCancellation = new ProcessCancellation($order, $user);
            $updateOrder = $processCancellation->checkCancellation($request->reason . '(' . auth()->guard('admin')->user()->name . ')');
            if ($updateOrder === true) {
                $order->user->myNotifications()->create(['title' => 'Order cancelled', 'message' => 'Your Order ' . $order->orderNo() . '  has been cancelled. Reason: ' . $request->reason, 'type' => 'order', 'task' => $order->orderNo()]);
                return response('success');
            } else {
                return response($updateOrder);
            }
        }
    }

    public function update(Request $request, $orderId)
    {
        $order = $this->orderService->query()->where('id', $orderId)->first();

        $validator = Validator::make($request->all(), [
            'status' => 'bail|nullable|in:DELIVERED',
            'deliveryDate' => 'bail|nullable',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return response($errors);
        }
        if ($order->status == "DELIVERED" || $order->status == "CANCELLED") {
            return response('Order already ' . $order->status);
        }

        if ($request->status == "DELIVERED") {
            $delivery = $order->deliveryRequest ?? false;
            $driver = $delivery->driver ??  false;
            $processDelivery = new ProcessDelivery($order, $order->user, $delivery, $driver);
            $delivered = $processDelivery->completeDelivery();
            if ($request->collectionType) {
                if ($order->payment_mode == "cash on delivery") {
                    if ($request->collectionType != 'paid') {
                        $colected = $processDelivery->deliveryCollection($request->collectionType);
                    }
                }
            } else {
                if ($order->payment_mode == "cash on delivery") {
                    $colected = $processDelivery->deliveryCollection('partial');
                }
            }

            $order->update(['status' => $request->status]);
            $order->user->myNotifications()->create(['title' => 'Order Completed', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);

            try {
                $token = $order->user->devices->pluck('device_token')->toArray();

                $notification = new PushNotification(
                    $token,
                    [
                        'title' => 'Order Delivered',
                        'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.',
                        'type' => 'order-delivered',
                    ]
                );
                $notification->send();
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        if ($request->deliveryDate) {
            $order->update(['date' => $request->deliveryDate]);
        }

        return response('success');
    }

    public function orderDetail(Request $request)
    {
        $order = $this->orderService->query()->where('id', $request->orderId)->first();
        return (new OrderDetailResource($order))->additional(['assocOrders' => OrderDetailResource::collection($this->orderService->query()->where('id', '!=', $request->orderId)->where('ref_number', $order->ref_number)->get())]);
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

            return response()->json(['status' => true, 'message' => 'Current Order is in marketplace now.']);
        }
    }


    public function assignDelivery(Request $request)
    {
        if (isset($request->type) && $request->type == "send") {
            // return response()->json(['status' => true, 'message' => 'Unable to perform right action from server']);
            $sendItem = Send::where('id', $request->sendId)->first();

            if (!$sendItem) {
                return failureResponse("Send item not found.", 404, 404);
            }
            $driver = Driver::where('id', $request->rider_id)->first();
            if (!$driver) {
                return failureResponse("Driver not found.", 404, 404);
            }
            $pickupOtp = randomNumericString(4);
            $sendItem->update(['status' => 2, 'pickup_driver_id' => $driver->id, 'pickup_otp' => $pickupOtp]); //Assigned to Pickup
            $driver->status()->update(['status' => 'ongoing']);
            // //Send notification to trip creator (user/customer)
            $userDeviceToken = $sendItem->user->devices->pluck('device_token')->toArray();
            $driverDeviceToken = $driver->devices->pluck('device_token')->toArray();

            try {
                $notification = new PushNotification(
                    $userDeviceToken,
                    [
                        'title' => 'Send Request Assigned',
                        'message' => 'Your Send Request has been assigned to delivery rider.',
                        'type' => 'send-request',
                    ]
                );
                $notification = new PushNotification(
                    $driverDeviceToken,
                    [
                        'title' => 'Send Request Assigned',
                        'message' => 'You have been assigned to delivery a send request.',
                        'type' => 'send-request',
                    ]
                );
                $notification->send();
            } catch (\Throwable $th) {
                throw $th;
            }
            return response()->json([
                'status' => true,
                'message' => 'Delivery has been assigned for you. Please be available at the Customer location ASAP.',
                'otp' => $pickupOtp,
                'statusCode' => 200
            ], 200);
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
                // $ridersToken = array_merge($ridersToken, $driver->devices->pluck('device_token')->toArray();
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
                $order->update(['status' => 'ASSIGNED TO RIDER']);
                $order->user->myNotifications()->create(['title' => 'Order Assigned', 'message' => 'Your Order ' . $order->orderNo() . '  has been assigned to delivery rider.', 'type' => 'order', 'task' => $order->orderNo()]);

                $driver->status()->update(['status' => 'ongoing']);
                //Send notification to trip creator (user/customer)
                try {
                    $userDeviceToken = $delivery->order->user->devices->pluck('device_token')->toArray();
                } catch (\Throwable $th) {
                    $userDeviceToken = [];
                }
                $driverDeviceToken = $delivery->driver->devices->pluck('device_token')->toArray();

                try {
                    $this->triggerNotificationforDriver($driverDeviceToken, $delivery);
                    $this->triggerNotificationforUser($userDeviceToken, $delivery, $driver, $verificationCode);
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
        $temps = json_decode($this->firebase->get(env('RIDER_DELIVERY_TEMP_PATH', 'riderTempDeliveries/') . $deliveryId));
        if (!empty($temps)) {
            foreach ($temps as $key => $riderId) {
                $this->firebase->delete(env('RIDER_DELIVERY_PATH', 'riderDeliveries/') . $riderId);
            }
            $this->firebase->delete(env('RIDER_DELIVERY_TEMP_PATH', 'riderTempDeliveries/') . $deliveryId);
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
            $ridersToken =  array_merge($ridersToken, $riderStatus->driver->devices->pluck('device_token')->toArray());
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
            'rider' => $delivery->driver ? new DriverResource($delivery->driver) : null,
            'delivery' => new RideDeliveryResource($delivery),
        ];

        $this->firebase->set(env('DELIVERY_PATH', 'deliveries/') . $delivery->id, $deliveryArray);
    }

    public function riderFirebaseRTD($driverId, Order $order, Delivery $delivery)
    {
        $tripArray = [
            'order' => new RideOrderResource($order),
            'delivery' => new RideDeliveryResource($delivery),
        ];

        $this->firebase->set(env('RIDER_DELIVERY_PATH', 'riderDeliveries/') . $driverId, $tripArray);
    }

    public function riderTempFirebaseRTD($driverIds, $deliveryId)
    {
        $this->firebase->set(env('RIDER_DELIVERY_TEMP_PATH', 'riderTempDeliveries/') . $deliveryId, $driverIds);
    }
}
