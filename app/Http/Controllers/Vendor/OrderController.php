<?php

namespace App\Http\Controllers\Vendor;

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
        return OrderResource::collection($this->orderService->query()->oldest()->where('vendor_id', auth()->id())->Where('accepted', 0)->Where('status', '!=', 'CANCELLED')->paginate($this->paginationLimit));
    }

    public function store(Request $request)
    {
        try {
            $order = Order::findOrFail($request->orderNo);
            $order->update(['status' => $request->status, 'date' => $request->deliveryDate ? $request->deliveryDate : $order->date]);
            $user = User::find($request->user);

            // Mail::to($user->email)->send(new OrderUpdate($order));

            try {
                $token = $user->device->device_token;
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
        $order = $this->orderService->delete($orderId);

        return response('success');
    }

    public function search(Request $request)
    {
        return OrderResource::collection($this->orderService->getAdvancedData($request->name));
    }

    public function acceptOrder(Request $request)
    {
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order->update(['accepted' => 1, 'status' => 'CONFIRMED']);
        return response('success');
    }

    public function acceptedOrderList()
    {
        $orders = $this->orderService->query()->where('accepted', 1)->Where('vendor_id', auth()->id())->with('deliveryRequest')->get();
        return OrderResource::collection($orders);
    }

    public function cancelOrder(Request $request)
    {
        $order = $this->orderService->query()->where('id', $request->orderId)->Where('vendor_id', auth()->id())->first();
        $order->update(['status' => 'CANCELLED', 'reason' => $request->reason ?? '', 'refundable_amount' => $order->payment_mode != "cod" ? $order->total : 0]);
        $notification = new PushNotification(
            [$order->user->device->device_token],
            [
                'title' => 'Cancelled By Vendor',
                'message' => 'Your Order ' . $order->ref_number . '  has been cancelled by Vendor. Reason: ' . $request->reason,
                'type' => 'order-cancelled',
            ]
        );
        $notification->send();
        return response('success');
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
