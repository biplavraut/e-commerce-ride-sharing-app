<?php

namespace App\Http\Controllers\Api\Driver;

use App\Custom\Order\ProcessDelivery;
use App\Order;
use App\Driver;
use App\Delivery;
use Firebase\FirebaseLib;
use App\Mail\OrderInvoice;
use Illuminate\Http\Request;
use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PrescriptionResource;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Api\Ride\OrderResource;
use App\Http\Resources\Api\Ride\DriverResource;
use App\Http\Resources\Api\Ride\DeliveryResource;
use App\InHouseRiderPaymentLog;
use App\Prescription;
use Exception;

class DeliveryController extends Controller
{
    protected $firebase;
    // protected $path = 'test/deliveries/';
    // protected $riderPath = 'test/riderDeliveries/';
    // protected $riderTempPath = 'test/riderTempDeliveries/';
    protected $firebaseURL = 'https://gogo20-292702.firebaseio.com/';
    protected $firebaseSecret = 'jfdgoAhbPyGqzllfRbYFU8pdt1qI29XHRQKlRy3T';

    public function __construct()
    {
        $this->firebase = new FirebaseLib($this->firebaseURL, $this->firebaseSecret);
    }

    public function acceptDelivery(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$delivery = Delivery::with('order')->find($request->deliveryId)) {
            return failureResponse("Delivery not found.", 404, 404);
        }


        if ($delivery->driver_id) {
            return failureResponse("Delivery has already been assigned.", 418, 418);
        }

        if (!$driver->verified) {
            return failureResponse("You're not verified rider. Please verify your vehilce detail to accept delivery request.", 404, 404);
        }

        $verificationCode = randomNumericString(4);

        $delivery->update(['driver_id' => $driver->id, 'status' => 'ongoing', 'otp' => $verificationCode]);
        $delivery->order->update(['status' => 'ASSIGNED TO RIDER']);
        $driver->status()->update(['status' => 'ongoing']);

        //Send notification to trip creator (user/customer)
        try {
            $this->triggerNotification($delivery->order->user->devices->pluck('device_token')->toArray(), $delivery, $driver, $verificationCode);
            $this->firebaseRTD($delivery->order, $delivery);
            $this->firebaseRiderTempRTD($delivery->id);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json([
            'status' => true,
            'message' => 'Delivery has been assigned for you. Please be available at the Customer location ASAP.',
            'otp' => $verificationCode,
            'statusCode' => 200
        ], 200);
    }

    public function ongoingDelivery()
    {
        $driver = auth()->guard('driver-api')->user();
        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $deliveries = Delivery::where('driver_id', $driver->id)->whereIn('status', ['pending', 'paused', 'ongoing', 'arrived', 'started', 'delivered'])->latest()->get();
        return DeliveryResource::collection($deliveries)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function deliveryPayment(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$delivery = Delivery::where('driver_id', $driver->id)->Where('order_id', $request->orderId)->first()) {
            return failureResponse("Order not found.", 404, 404);
        }
        if ($request->collectionType) {
            if ($delivery->order->payment_mode == "cash on delivery") {
                // if ($request->collectionType = 'paid') {
                $processDelivery = new ProcessDelivery($delivery->order, $delivery->order->user, $delivery, $driver);
                $collected = $processDelivery->deliveryCollection($request->collectionType);
                // }
            }
        } else {
            if ($delivery->order->payment_mode == "cash on delivery") {
                $processDelivery = new ProcessDelivery($delivery->order, $delivery->order->user, $delivery, $driver);
                $collected = $processDelivery->deliveryCollection('partial');
            }
        }

        $delivery->update(['status' => 'collected']);
        // if ($driver->settlement) {
        //     $payable = $order->total < 100 ? 5 : 6;
        //     $driver->settlement->update(['payable_amount' => $driver->settlement->payable_amount + $payable]);
        // } else {
        //     $payable = $order->price < 100 ? 5 : 6;
        //     $driver->settlement()->create(['payable_amount' => $payable]);
        // }
        try {
            $order = Order::with('orderItems')->where("id", $request->orderId)->first();
            $this->orderInvoice($order);
        } catch (\Throwable $th) {
            //throw $th;
        }

        // donation cod
        if ($request->donationTrust && $request->donation) {
            $this->donationProcess($request->donationTrust, $request->donation, $delivery->order->user);
        }
        return successResponse('Payment has been set to Received.', 200, 200);
    }


    public function arrivedToPickupPoint(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$delivery = Delivery::find($request->deliveryId)) {
            return failureResponse("Delivery not found.", 404, 404);
        }

        if ($delivery->status == "ongoing") {
            $delivery->update(['status' => 'arrived']);
        }

        $delivery->order->update(['status' => 'PICKING UP BY RIDER']);
        $delivery->order->user->myNotifications()->create(['title' => 'Order Picking Up', 'message' => 'Delivery Rider is picking up your order ' . $delivery->order->orderNo() . '  from store.', 'type' => 'order', 'task' => $delivery->order->orderNo()]);



        $this->firebaseRTD($delivery->order, $delivery);

        return successResponse('Success.', 200, 200);
    }

    public function deliveryStart(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        $message = "";

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$delivery = Delivery::find($request->deliveryId)) {
            return failureResponse("Delivery not found.", 404, 404);
        }

        if ($delivery->status == "arrived") {
            $delivery->update(['status' => 'started']);
        } else {
            $delivery->order->update(['status' => 'ON THE WAY']);
            $delivery->order->user->myNotifications()->create(['title' => 'Order Status', 'message' => 'Your Order ' . $delivery->order->orderNo() . '  is on the way.', 'type' => 'order', 'task' => $delivery->order->orderNo()]);
        }


        $this->firebaseRTD($delivery->order, $delivery);

        return successResponse('Success.', 200, 200);
    }


    public function deliveryCompleted(Request $request)
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        if (!$delivery = Delivery::find($request->deliveryId)) {
            return failureResponse("Delivery not found.", 404, 404);
        }
        $processDelivery = new ProcessDelivery($delivery->order, $delivery->order->user, $delivery, $driver);
        $delivered = $processDelivery->completeDelivery();
        if ($delivered === true) {
            // if ($driver->is_associated_rider == 0) {
            //     if ($driver->settlement) {
            //         if ($delivery->order->payment_mode == "cash on delivery" && $delivery->status == "started") {
            //             $myCommission = $delivery->order->total < 500 ? 5 : 6;
            //             $payable = $delivery->order->total - $myCommission;
            //             $driver->settlement->update(['payable_amount' => $driver->settlement->payable_amount + $payable]);
            //         } elseif ($delivery->order->payment_mode != "cash on delivery" && $delivery->status == "started") {
            //             $receivable = $delivery->order->total < 500 ? 5 : 6;
            //             $driver->settlement->update(['receivable_amount' => $driver->settlement->receivable_amount + $receivable]);
            //         }
            //     } else {
            //         if ($delivery->order->payment_mode == "cash on delivery" && $delivery->status == "started") {
            //             $myCommission = $delivery->order->total < 500 ? 5 : 6;
            //             $payable = $delivery->order->total - $myCommission;
            //             $driver->settlement()->create(['payable_amount' => $payable]);
            //         } elseif ($delivery->order->payment_mode != "cash on delivery" && $delivery->status == "started") {
            //             $receivable = $delivery->order->total < 500 ? 5 : 6;
            //             $driver->settlement()->create(['receivable_amount' => $receivable]);
            //         }
            //     }
            // } else {
            //     $log = InHouseRiderPaymentLog::create(['order_id' => $delivery->order_id, 'driver_id' => $delivery->driver_id, 'total' => $delivery->order->total]);
            // }

            $delivery->update(['status' => 'delivered', 'delivered_at' => now()]);
            $driver->status()->update(['status' => 'active']);
            // $delivery->order->update(['status' => 'DELIVERED', 'date' => now()]);

            // $this->firebaseRiderLocationDelRTD($driver->id);
            $this->firebaseRTD($delivery->order, $delivery);

            // $this->firebaseTripDelRTD($trip->id);
            $delivery->order->user->myNotifications()->create(['title' => 'Order status', 'message' => 'Your Order ' . $delivery->order->orderNo() . ' has been marked as delivered.', 'type' => 'order', 'task' => $delivery->order->orderNo()]);

            return successResponse('Delivery has been set to completed.', 200, 200);
        } else {
            if ($delivered === false) {
                return failureResponse("Something Went wrong.", 404, 404);
            } else {
                return $delivered;
            }
        }
    }


    public function triggerNotification(array $token, $delivery, Driver $driver, $otp)
    {
        $notification = new PushNotification(
            $token,
            [
                'title' => 'Accepted by Rider',
                'delivery' => new DeliveryResource($delivery),
                'rider' => new DriverResource($driver),
                'type' => 'accept',
                'otp' => $otp
            ]
        );
        $notification->send();
    }

    public function firebaseRTD(Order $order, Delivery $delivery)
    {
        $deliveryArray = [
            'order' => new OrderResource($order),
            'delivery' => new DeliveryResource($delivery),
            'rider' => $delivery->driver ? new DriverResource($delivery->driver) : null,
        ];

        $this->firebase->set(env('DELIVERY_PATH', 'deliveries/') . $delivery->id, $deliveryArray);
    }

    public function firebaseRiderTempRTD($deliveryId)
    {
        $temps = json_decode($this->firebase->get(env('RIDER_DELIVERY_TEMP_PATH', 'riderTempDeliveries/') . $deliveryId));

        foreach ($temps as $key => $riderId) {
            $this->firebase->delete(env('RIDER_DELIVERY_PATH', 'riderDeliveries/') . $riderId);
        }
        $this->firebase->delete(env('RIDER_DELIVERY_TEMP_PATH', 'riderTempDeliveries/') . $deliveryId);
    }

    public function history()
    {
        $driver = auth()->guard('driver-api')->user();

        if (!$driver) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return DeliveryResource::collection($driver->deliveryHistories)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function orderInvoice($order)
    {
        if ($order->user->email != '') {
            // try {
            Mail::to($order->user)->send(new OrderInvoice($order));
            // } catch (\Throwable $th) {
            //     throw $th;
            // }
        }
    }

    public function donationProcess($trust, $amount, $user)
    {
        $user->donations()->create(['trust' => $trust, 'donation' => $amount]);
    }
}
