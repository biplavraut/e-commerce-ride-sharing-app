<?php

namespace App\Services;

use App\Helper\ResponseMessage;
use App\Http\Requests\Api\OrderRequest;
use App\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService extends ModelService
{
    const MODEL = Order::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        $orders = $this->query()->oldest()->paginate($limit, $columns);
    }

    public function insert(OrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->guard('api')->user();
            $refNumber = "GGRef" . date("Ymd") . rand(11111, 99999) . $user->id;
            $vendorIds = $orders = [];
            //check for product's vendor and create package
            foreach ($request->items as $item) {
                $vendorIds[] = $item['vendorId'];
            }
            $uniqueVendorIds = array_unique($vendorIds);

            foreach ($uniqueVendorIds as  $value) {
                $order                  =    new Order();
                $order->user_id         =   $user->id;
                $order->vendor_id       =   $value;
                $order->subtotal        =   0;
                $order->shipping_fee    =   $request->shippingFee;
                $order->total           =   0;
                $order->location        =   $request->address;
                $order->delivery_location = $request->deliveryArea;
                $order->lat             =   $request->latitude;
                $order->long            =   $request->longitude;
                $order->order_by        =   $user->first_name . " " . $user->last_name;
                $order->phone           =   $user->country_code . $user->phone;
                $order->email           =   $user->email;
                $order->status          =   "PENDING";
                $order->payment_mode    =   $request->paymentType == "cod" ? "cash on delivery" : $request->paymentType;
                // $order->date            =   Carbon::now();
                $order->ref_number      =    $refNumber;
                $order->save();
                $orders[] = $order;
            }

            DB::commit();
            return $orders;
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseMessage::ERROR;
        }
    }

    public function updateTotal($orders)
    {
        $wholeTotal = 0;
        foreach ($orders as $order) {
            $subtotal   =   0;

            foreach ($order->orderItems as $key => $orderItem) {
                $itemTotal          =   $orderItem->price * $orderItem->quantity;
                $itemDiscount       =   ($orderItem->discount_type == 'percent') ? $orderItem->price * $orderItem->discount / 100 : $orderItem->discount;
                $itemTotalDiscount  =   $itemDiscount * $orderItem->quantity;
                $subtotal           =   $subtotal + ($itemTotal - $itemTotalDiscount);
            }
            $order->subtotal        =   $subtotal;
            $order->total           =   $subtotal + $order->shipping_fee;
            $order->save();
            $wholeTotal += $order->total;
        }
        return $wholeTotal;
    }

    public function getAdminAdvancedData($name)
    {
        if (!$name) {
            return collect([]);
        }

        return $this->query()->where('accepted', 1)
            ->Where('location', 'LIKE', '%' . $name . '%')
            ->orWhere('order_by', 'LIKE', $name . '%')
            ->orWhere('status', 'LIKE', $name . '%')
            ->orWhere('payment_mode', 'LIKE', $name . '%')
            ->orWhere('ref_number', 'LIKE', $name . '%')
            ->orWhere('date', 'LIKE', $name . '%')
            ->take(10)
            ->get();
    }

    public function getAdvancedData($name)
    {
        if (!$name) {
            return collect([]);
        }

        $byPhone = $this->query()->where('vendor_id', auth()->id())->where('phone', '+' . $name)->get();

        if ($byPhone->count() > 0) {
            return $byPhone;
        }

        $byName = $this->query()->where('vendor_id', auth()->id())->where('order_by', $name)->get();


        if ($byName->count() > 0) {
            return $byName;
        }


        return $this->query()->where('vendor_id', auth()->id())
            ->Where('location', 'LIKE', '%' . $name . '%')
            ->orWhere('order_by', 'LIKE', $name . '%')
            ->orWhere('status', 'LIKE', $name . '%')
            ->orWhere('payment_mode', 'LIKE', $name . '%')
            ->orWhere('ref_number', 'LIKE', $name . '%')
            ->orWhere('date', 'LIKE', $name . '%')
            ->take(10)
            ->get();
    }

    public function getAcceptedCount()
    {
        return $this->query()->where('status', '!=', 'DELIVERED')->where('status', '!=', 'CANCELLED')->count();
    }
}
