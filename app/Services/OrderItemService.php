<?php

namespace  App\Services;

use App\Helper\ResponseMessage;
use App\Http\Requests\Api\OrderItemRequest;
use App\Http\Requests\Api\OrderRequest;
use App\Order;
use App\OrderItem;
use App\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderItemService extends ModelService
{
    const  MODEL =  OrderItem::class;

    public function insert($orders, OrderRequest $orderRequest)
    {
        try {
            DB::beginTransaction();
            $user = auth()->guard('api')->user();
            foreach ($orders as $key => $order) {
                foreach ($orderRequest->items as $item) {
                    $product    =    Product::where('id', $item['productId'])->first();
                    if ($product) {
                        if ($product->opening_stock >= $item['quantity']) {
                            if ($item['vendorId'] == $order['vendor_id']) {
                                $orderItem                  =    new OrderItem();
                                $orderItem->user_id        =   $user->id;
                                $orderItem->order_id        =   $order['id'];
                                $orderItem->product_id      =   $product->id;
                                $orderItem->vendor_id      =   $product->vendor_id;
                                $orderItem->name            =   $product->title;
                                $orderItem->price           =   $product->price;
                                $orderItem->discount_type   =   $product->discount_type;
                                $orderItem->discount        =   $product->discount;
                                $orderItem->quantity        =   $item['quantity'];
                                $orderItem->color           =   $item['color'];
                                $orderItem->size            =   $item['size'];
                                $orderItem->save();
                                Product::where('id', $product->id)->update(['opening_stock' => $product->opening_stock - $item['quantity']]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return ResponseMessage::OrderSuccess;
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseMessage::ERROR;
        }
    }
}
