<?php

namespace  App\Services;

use App\Order;
use App\OrderItem;
use App\OrderOfferConf;
use Exception;
use App\Product;
use App\UserCart;
use App\Helper\ResponseMessage;
use App\Custom\PushNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Requests\Api\OrderItemRequest;

class OrderItemService extends ModelService
{
    const  MODEL =  OrderItem::class;

    public function insert($orders, OrderRequest $orderRequest)
    {
        try {
            DB::beginTransaction();
            $user = auth()->guard('api')->user();
            foreach ($orders as $key => $order) {
                foreach ($user->cart as $item) {
                    $product    =    Product::where('id', $item->product_id)->first();
                    if ($product) {
                        if ($product->opening_stock >= $item->quantity) {
                            if ($item->product->vendor_id == $order['vendor_id']) {
                                $orderItem = new OrderItem();
                                $orderItem->user_id        =   $user->id;
                                $orderItem->order_id        =   $order['id'];
                                $orderItem->product_id      =   $product->id;
                                $orderItem->vendor_id      =   $product->vendor_id;
                                $orderItem->name            =   $product->title;
                                $orderItem->price           =   $product->price;
                                $orderItem->gogo_price           = $product->price_1;
                                $orderItem->elite_price           = round(($product->price * $product->elite_percent) / 100);
                                $orderItem->tax_amt           = $product->vat_percentage;
                                $orderItem->service_charge_amt  = $product->service_charge_percentage;
                                $orderItem->discount_type   =   $product->discount_type;
                                $orderItem->discount        =   $product->discount;
                                $orderItem->quantity        =   $item->quantity;
                                $orderItem->color           =   $item->color;
                                $orderItem->size            =   $item->size;
                                $orderItem->special_instruction        =   $item->special_instruction;
                                $orderItem->date           =   $item->date;
                                $orderItem->time            =   $item->time;
                                $orderItem->save();
                                Product::where('id', $product->id)->update(['opening_stock' => $product->opening_stock - $item->quantity]);
                            }
                        } else {
                            DB::rollBack();
                            return ResponseMessage::OUTOFSTOCK;
                        }
                    }
                }

                $this->triggerPushNotification($order);
            }

            DB::commit();
            return ResponseMessage::OrderSuccess;
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseMessage::ERROR;
        }
    }

    public function triggerPushNotification($order)
    {
        try {
            $notification = new PushNotification(
                $order->vendor->devices->pluck('device_token')->toArray(),
                [
                    'title' => 'Order Received',
                    'message' => 'New order received. Please check now.',
                    'type' => 'order-received',
                ]
            );
            $notification->send();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
