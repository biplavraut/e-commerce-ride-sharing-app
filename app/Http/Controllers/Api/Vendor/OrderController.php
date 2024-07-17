<?php

namespace App\Http\Controllers\Api\Vendor;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Custom\PushNotification;
use App\Services\DeliveryService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\OrderResource;
use App\Http\Controllers\Api\CommonController;

class OrderController extends CommonController
{
    /** @var OrderService */
    private $orderService;

    /** @var DeliveryService */
    private $deliveryService;


    public function __construct(OrderService $orderService, DeliveryService $deliveryService)
    {
        $this->orderService    =   $orderService;
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

    public function cancelOrder(Request $request)
    {
        $user = auth()->guard('vendor-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', $user->id)->first();
        $order->update(['status' => 'CANCELLED', 'reason' => $request->reason ?? '', 'refundable_amount' => $order->payment_mode != "cod" ? $order->total : 0]);
        $notification = new PushNotification(
            [$order->user->device_token],
            [
                'title' => 'Cancelled By Vendor',
                'message' => 'Your Order ' . $order->ref_number . '  has been cancelled by Vendor. Reason: ' . $request->reason,
                'type' => 'order-cancelled',
            ]
        );
        $notification->send();
        return successResponse("Order has been cancelled.");
    }

    public function deleteItem(Request $request)
    {
        $orderItem = $this->orderItemService->query()->where('id', $request->itemId)->where('order_id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $itemPrice = $orderItem->price * $orderItem->quantity;
        $order->update(['total' => ($order->total - $itemPrice), 'refundable_amount' => $itemPrice]);
        $orderItem->delete();
        return response('success');
    }

    public function updateItem(Request $request)
    {
        $orderItem = $this->orderItemService->query()->where('id', $request->itemId)->where('order_id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $updatedQuantity = $orderItem->quantity - $request->quantity;

        if ($updatedQuantity < 1) {
            $final = $orderItem->price * ($request->quantity - $orderItem->quantity);
            $orderItem->update(['quantity' =>  $request->quantity]);
            $order->update(['total' => ($order->total + $final), 'refundable_amount' => $order->payment_mode != "cod" ? $order->refundable_amount + (-$final) : 0]);
        } else {
            $final = $orderItem->price * $updatedQuantity;
            $orderItem->update(['quantity' => $request->quantity]);
            $order->update(['total' => ($order->total - $final), 'refundable_amount' => $order->payment_mode != "cod" ? $order->refundable_amount + $final : 0]);
        }
        return response('success');
    }
}
