<?php

namespace App\Http\Controllers\Vendor;

use App\Custom\Order\ProcessCancellation;
use App\Custom\Order\ProcessDelivery;
use App\User;
use App\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Custom\PushNotification;
use App\Services\OrderItemService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Vendor\OrderResource;
use App\Http\Resources\Admin\ProductResource;
use App\VendorAdvanceSettlement;

class OrderController extends CommonController
{

    /** @var OrderService */
    private $orderService;

    /** @var OrderItemService */
    private $orderItemService;


    public function __construct(OrderService $orderService, OrderItemService $orderItemService)
    {
        parent::__construct();
        $this->orderService    =   $orderService;
        $this->orderItemService    =   $orderItemService;
    }

    public function index()
    {
        return OrderResource::collection($this->orderService->query()->latest()->where('vendor_id', auth()->id())->Where('accepted', 0)->Where('status', '!=', 'CANCELLED')->Where('status', '!=', 'DELIVERED')->paginate($this->paginationLimit));
    }

    public function takeAwayList()
    {
        return OrderResource::collection($this->orderService->query()->latest()->where('vendor_id', auth()->id())->Where('accepted', 1)->Where('takeaway', 1)->Where('status', '!=', 'CANCELLED')->Where('status', '!=', 'DELIVERED')->paginate($this->paginationLimit));
    }

    public function store(Request $request)
    {
        try {
            $order = Order::findOrFail($request->orderNo);
            $order->update(['status' => $request->status, 'date' => $request->deliveryDate ? $request->deliveryDate : $order->date]);
            $user = User::find($request->user);

            // Mail::to($user->email)->send(new OrderUpdate($order));

            try {
                $token = $order->user->devices->pluck('device_token')->toArray();
                if ($token) {
                    // $this->messageSend($request, $token);
                }
            } catch (\Throwable $th) {
            }

            return response()->json(['status' => true, 'code' => 200, 'message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'code' => 424, 'message' => 'error']);
        }
    }

    public function show($orderId)
    {
        $order = $this->orderService->query()->where('id', $orderId)->Where('vendor_id', auth()->id())->first();
        return new OrderResource($order);
    }

    public function update(Request $request, $orderId)
    {
    }

    public function destroy($orderId)
    {
        // $order = $this->orderService->delete($orderId);

        // return response('success');
    }

    public function search(Request $request)
    {
        return OrderResource::collection($this->orderService->getAdvancedData($request->name));
    }

    public function acceptOrder(Request $request)
    {
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order->update(['accepted' => 1, 'status' => 'CONFIRMED']);
        $order->user->myNotifications()->create(['title' => 'Order Confirmed', 'message' => 'Your Order ' . $order->orderNo() . '  has been confirmed.', 'type' => 'order', 'task' => $order->orderNo()]);

        try {
            $notification = new PushNotification(
                $order->user->devices->pluck('device_token')->toArray(),
                [
                    'title' => 'Order Confirmed',
                    'message' => 'Your order ' . $order->orderNo() . ' has been confirmed.',
                    'type' => 'order-confirmed',
                ]
            );
            $notification->send();
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response('success');
    }

    public function acceptedOrderList()
    {
        $orders = $this->orderService->query()->where('accepted', 1)->Where('vendor_id', auth()->id())->where('status', 'ASSIGNED TO RIDER')->with('deliveryRequest')->paginate($this->paginationLimit);
        return OrderResource::collection($orders);
    }

    public function cancelOrder(Request $request)
    {
        try {
            $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
            if (!$order) {
                return failureResponse("Order not found.", 404, 404);
            }
            $user = $order->user;
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


                    return response('success');
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
        // $order->update(['status' => 'CANCELLED', 'reason' => $request->reason ?? '', 'refundable_amount' => $order->payment_mode != "cash on delivery" ? $order->total : 0]);

    }

    public function serviceOrderDelivered(Request $request)
    {
        return $request;
        DB::transaction(function () use ($request) {
            try {
                $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
                if (!$order) {
                    return failureResponse("Order not found.", 404, 404);
                }
                $delivery = $order->deliveryRequest ?? false;
                $driver = $delivery->driver ??  false;
                $processDelivery = new ProcessDelivery($order, $order->user, $delivery, $driver);
                $delivered = $processDelivery->completeDelivery();
                $order->additionalDetail->update(['total_collected' => $order->additionalDetail->order_total]);
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

                $payment = VendorAdvanceSettlement::create(['vendor_id' => $order->vendor_id, 'amount' => ($order->total - $order->paying_total), 'remarks' => 'Advanced added from takeaway order.']);

                $order->user->myNotifications()->create(['title' => 'Order Delivered', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);
                return response('success');
            } catch (\Exception $th) {
                return $th;
            }
        });

        return response('success');
    }

    public function markAsDelivered(Request $request)
    {
        DB::transaction(function () use ($request) {
            try {
                $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->Where('takeaway', 1)->first();
                if (!$order) {
                    return failureResponse("Order not found.", 404, 404);
                }
                $delivery = $order->deliveryRequest ?? false;
                $driver = $delivery->driver ??  false;
                $processDelivery = new ProcessDelivery($order, $order->user, $delivery, $driver);
                $delivered = $processDelivery->completeDelivery();
                $order->additionalDetail->update(['total_collected' => $order->additionalDetail->order_total]);
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

                $payment = VendorAdvanceSettlement::create(['vendor_id' => $order->vendor_id, 'amount' => ($order->total - $order->paying_total), 'remarks' => 'Advanced added from takeaway order.']);

                $order->user->myNotifications()->create(['title' => 'Order Delivered', 'message' => 'Your Order ' . $order->orderNo() . '  has been marked as delivered.', 'type' => 'order', 'task' => $order->orderNo()]);
                return response('success');
            } catch (\Exception $th) {
                return $th;
            }
        });

        return response('success');
    }


    public function deleteItem(Request $request)
    {
        return failureResponse("Service unavailable.", 418, 418);
        $orderItem = $this->orderItemService->query()->where('id', $request->itemId)->where('order_id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $itemPrice = $orderItem->product->discount_price * $orderItem->quantity;
        $order->update(['total' => ($order->total - $itemPrice), 'refundable_amount' => $order->payment_mode != "cash on delivery" ? $itemPrice : $order->refundable_amount]);
        $orderItem->delete();

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


        return response('success');
    }

    public function updateItem(Request $request)
    {
        return failureResponse("Service unavailable.", 418, 418);

        $orderItem = $this->orderItemService->query()->where('id', $request->itemId)->where('order_id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
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
        return response('success');
    }
}
