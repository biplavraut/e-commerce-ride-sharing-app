<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Custom\Order\ProcessCancellation;
use App\Custom\Order\ProcessDelivery;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Custom\PushNotification;
use App\VendorAdvanceSettlement;
use App\Services\DeliveryService;
use App\Services\OrderItemService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Vendor\OrderResource;
use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Vendor\OrderReturnResource;
use App\OrderReturn;

class OrderController extends CommonController
{
    /** @var OrderService */
    private $orderService;


    /** @var OrderItemService */
    private $orderItemService;

    /** @var DeliveryService */
    private $deliveryService;


    public function __construct(OrderService $orderService, OrderItemService $orderItemService, DeliveryService $deliveryService)
    {
        $this->orderService    =   $orderService;
        $this->orderItemService    =   $orderItemService;
        $this->deliveryService    =   $deliveryService;
    }

    public function orderList()
    {
        $user = auth()->guard('vendor-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }


        return OrderResource::collection($this->orderService->query()
            ->oldest()->where('vendor_id', $user->id)->Where('accepted', 0)->paginate($this->paginationLimit))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function orderDetail(Request $request)
    {
        $user = auth()->guard('vendor-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        try {
            $order = $this->orderService->query()->where('id', $request->orderId)->where('vendor_id', $user->id)->firstOrFail();
        } catch (\Throwable $th) {
            return failureResponse("Order Not Found.", 404, 404);
        }


        return (new OrderResource($order))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }


    public function acceptOrder(Request $request)
    {
        $user = auth()->guard('vendor-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        try {
            $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $user->id)->first();
            $order->update(['accepted' => 1, 'status' => 'CONFIRMED']);
        } catch (\Throwable $th) {
            return failureResponse("Order Not Found.", 404, 404);
        }

        return successResponse("Order Confirmed.", 200, 200);
    }

    public function acceptedOrderList()
    {
        $user = auth()->guard('vendor-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $orders = $this->orderService->query()->where('accepted', 1)->Where('vendor_id', $user->id)->with('deliveryRequest')->paginate($this->paginationLimit);
        return OrderResource::collection($orders)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    // public function cancelOrder(Request $request)
    // {
    //     $user = auth()->guard('vendor-api')->user();

    //     if (!$user) {
    //         return failureResponse("Token Expired.", 401, 401);
    //     }
    //     $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $user->id)->first();
    //     $order->update(['status' => 'CANCELLED', 'reason' => $request->reason ?? '', 'refundable_amount' => $order->payment_mode != "cod" ? $order->total : 0]);
    //     $notification = new PushNotification(
    //         [$order->user->devices->pluck('device_token')->toArray()],
    //         [
    //             'title' => 'Cancelled By Vendor',
    //             'message' => 'Your Order ' . $order->orderNo() . '  has been cancelled by Vendor. Reason: ' . $request->reason,
    //             'type' => 'order-cancelled',
    //         ]
    //     );
    //     $notification->send();
    //     return successResponse("Order has been cancelled.");
    // }

    public function cancelOrder(Request $request)
    {
        try {
            $user = auth()->guard('vendor-api')->user();
            if (!$user) {
                return failureResponse("Token Expired.", 401, 401);
            }
            $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $user->id)->first();
            if (!empty($order) && $order->status == "PENDING") {
                $processCancellation = new ProcessCancellation($order, $user);
                $updateOrder = $processCancellation->checkCancellation($request->reason . '(V)');
                if ($updateOrder === true) {
                    try {
                        $token = $order->user->devices->pluck('device_token')->toArray();
                        $notification = new PushNotification(
                            $token,
                            [
                                'title' => 'Cancelled By Vendor',
                                'message' => 'Your Order ' . $order->ref_number . '  has been cancelled by Vendor. Reason: ' . $request->reason,
                                'type' => 'order-cancelled',
                            ]
                        );
                        $notification->send();
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                    $order->user->myNotifications()->create(['title' => 'Order cancelled', 'message' => 'Your Order ' . $order->orderNo() . '  has been cancelled by Vendor. Reason: ' . $request->reason, 'type' => 'order', 'task' => $order->orderNo()]);


                    return successResponse("Order has been cancelled.");
                } else {
                    if ($updateOrder === false) {
                        return failureResponse("Unable to process order cancellation.", 418, 418);
                    } else {
                        return failureResponse($updateOrder, 418, 418);
                    }
                    // return failureResponse("Unable to process order cancellation.", 418, 418);
                }
            }
        } catch (\Exception $th) {
            return $th;
        }
        return successResponse("Order has been cancelled.");
        // $order->update(['status' => 'CANCELLED', 'reason' => $request->reason ?? '', 'refundable_amount' => $order->payment_mode != "cash on delivery" ? $order->total : 0]);

    }

    public function deleteItem(Request $request)
    {
        return failureResponse("Service unavailable.", 418, 418);
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'itemId' => 'required',
            'orderId' => 'required',

        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        try {
            $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $vendor->id)->first();
            $orderItem = $this->orderItemService->query()->where('id', $request->itemId)->where('order_id', $request->orderId)->Where('vendor_id', $vendor->id)->first();


            $itemPrice = $orderItem->product->discount_price * $orderItem->quantity;
            $order->update(['total' => ($order->total - $itemPrice), 'refundable_amount' => $order->payment_mode != "cash on delivery" ? $itemPrice : $order->refundable_amount]);

            if ($order->orderItems()->count() > 1) {
                try {
                    $token = $order->user->devices->pluck('device_token')->toArray();
                } catch (\Throwable $th) {
                    $token = [];
                }

                $notification = new PushNotification(
                    $token,
                    [
                        'title' => 'Item Cancelled By Vendor',
                        'message' => 'Your item ' . $orderItem->name . ' of order ' . $order->orderNo() . ' has been cancelled.',
                        'type' => 'item-cancelled',
                    ]
                );
                $notification->send();

                $order->user->myNotifications()->create(['title' => 'Order Item cancelled', 'message' => 'Your item ' . $orderItem->name . ' of order ' . $order->orderNo() . ' has been cancelled.', 'type' => 'order', 'task' => $order->orderNo()]);
            } else {
                try {
                    $token = $order->user->devices->pluck('device_token')->toArray();
                } catch (\Throwable $th) {
                    $token = [];
                }

                $notification = new PushNotification(
                    $token,
                    [
                        'title' => 'Order Cancelled By Vendor',
                        'message' => 'Your order ' . $order->orderNo() . ' has been cancelled.',
                        'type' => 'item-cancelled',
                    ]
                );
                $notification->send();

                $order->user->myNotifications()->create(['title' => 'Order cancelled', 'message' => 'Your order ' . $order->orderNo() . ' has been cancelled.', 'type' => 'order', 'task' => $order->orderNo()]);

                $order->update(['status' => 'CANCELLED']);
            }

            $orderItem->delete();
        } catch (\Throwable $th) {
            return failureResponse('Order or Order Item Not Found.', 400, 400);
        }

        return successResponse('Order Item successfully removed.');
    }

    public function updateItem(Request $request)
    {
        return failureResponse("Service unavailable.", 418, 418);
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'itemId' => 'required',
            'orderId' => 'required',
            'quantity' => 'required'

        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        try {
            $orderItem = $this->orderItemService->query()->where('id', $request->itemId)->where('order_id', $request->orderId)->Where('vendor_id', $vendor->id)->first();
            $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $vendor->id)->first();
            $updatedQuantity = $orderItem->quantity - $request->quantity;

            if ($updatedQuantity < 1) {
                $final = $orderItem->product->discount_price * ($request->quantity - $orderItem->quantity);
                $orderItem->update(['quantity' =>  $request->quantity]);
                $order->update(['total' => ($order->total + $final), 'refundable_amount' => $order->payment_mode != "cash on delivery" ? $order->refundable_amount + (-$final) : $order->refundable_amount]);
            } else {
                $final = $orderItem->product->discount_price * $updatedQuantity;
                $orderItem->update(['quantity' => $request->quantity]);
                $order->update(['total' => ($order->total - $final), 'refundable_amount' => $order->payment_mode != "cash on delivery" ? $order->refundable_amount + $final : $order->refundable_amount]);
            }
        } catch (\Throwable $th) {
            return failureResponse('Order/Item Not Found.', 400, 400);
        }

        return successResponse('Order Item quantity successfully updated.');
    }

    public function takeAwayList()
    {
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $orders =  $this->orderService->query()->latest()->where('vendor_id', $vendor->id)->Where('accepted', 1)->Where('takeaway', 1)->Where('status', '!=', 'CANCELLED')->Where('status', '!=', 'DELIVERED')->paginate($this->paginationLimit);

        return OrderResource::collection($orders)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function serviceOrderDelivered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderId' => 'required',

        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        DB::transaction(function () use ($request) {
            try {
                $vendor = auth()->guard('vendor-api')->user();

                if (!$vendor) {
                    return failureResponse("Token Expired.", 401, 401);
                }

                try {
                    $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $vendor->id)->first();
                } catch (\Throwable $th) {
                    return failureResponse('Order Not Found.', 400, 400);
                }
                $delivery = $order->deliveryRequest ?? false;
                $driver = $delivery->driver ??  false;
                $processDelivery = new ProcessDelivery($order, $order->user, $delivery, $driver);
                $delivered = $processDelivery->completeDelivery();
                $order->additionalDetails->update(['total_collected' => $order->additionalDetails->total_collected + $order->total]);
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
                    $order->user->myNotifications()->create(['title' => 'Order Completed', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);
                } catch (\Throwable $th) {
                    throw $th;
                }

                $order->update(['status' => 'DELIVERED', 'settle_status' => 1, 'settlement_date' => now()]);

                $payment = VendorAdvanceSettlement::create(['vendor_id' => $order->vendor_id, 'amount' => ($order->total - $order->paying_total), 'remarks' => 'Advanced added from Service order.']);

                $order->user->myNotifications()->create(['title' => 'Order Delivered', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);
                return successResponse('Order successfully marked as Delivered.');
            } catch (\Exception $th) {
                return $th;
            }
        });

        return successResponse('Order successfully marked as Delivered.');
    }

    public function markAsDelivered(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'orderId' => 'required',

        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        DB::transaction(function () use ($request) {
            try {
                $vendor = auth()->guard('vendor-api')->user();

                if (!$vendor) {
                    return failureResponse("Token Expired.", 401, 401);
                }

                try {
                    $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $vendor->id)->Where('takeaway', 1)->first();
                } catch (\Throwable $th) {
                    return failureResponse('Order Not Found.', 400, 400);
                }
                $delivery = $order->deliveryRequest ?? false;
                $driver = $delivery->driver ??  false;
                $processDelivery = new ProcessDelivery($order, $order->user, $delivery, $driver);
                $delivered = $processDelivery->completeDelivery();
                try {
                    if ($order->payment_mode == "cash on delivery") {
                        $order->additionalDetails->update(['total_collected' => $order->additionalDetails->order_total]);
                        $order->update(['status' => 'DELIVERED', 'settle_status' => 1, 'settlement_date' => now()]);
                        $payment = VendorAdvanceSettlement::create(['vendor_id' => $order->vendor->id, 'amount' => ($order->total - $order->paying_total), 'remarks' => 'Advanced added from takeaway order.']);
                    } else {
                        $order->update(['status' => 'DELIVERED']);
                    }
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
                    $order->user->myNotifications()->create(['title' => 'Order Completed', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);
                } catch (\Exception $ex) {
                    throw $th;
                }

                $order->user->myNotifications()->create(['title' => 'Order Delivered', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);
                return successResponse('Order successfully marked as Delivered.');
            } catch (\Exception $th) {
                return $th;
            }
        });
        return successResponse('Order successfully marked as Delivered.');
    }

    public function returnedOrderList()
    {
        $user = auth()->guard('vendor-api')->user();
        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $returns = OrderReturn::whereIn('status', ['proceed-to-vendor', 'accepted-by-vendor', 'declined-by-vendor'])->Where('vendor_id', $user->id)->with('user', 'order', 'orderItem', 'product')->paginate(10);
        // return response()->json([
        //     'data' => $returns,
        //     'status' => true,
        //     'message' => "",
        //     'statusCode' => 200
        // ], 200);
        return OrderReturnResource::collection($returns)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function updateReturnedOrder(Request $request)
    {
        $user = auth()->guard('vendor-api')->user();
        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $returns = OrderReturn::Where('id', $request->id)->where('status', '!=', 'resolved')->firstOrFail();
        if (!$returns) {
            return failureResponse("Return Request not found or resolved.", 401, 401);
        }
        $returns->update(['remarks' => $returns->remarks . "\r\n" . $request->remarks . '(V)', 'status' => $request->status]);

        return successResponse('Return status Updated.');
    }
}
