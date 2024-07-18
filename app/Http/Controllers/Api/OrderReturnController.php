<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderReturnRequest;
use App\OrderItem;
use App\OrderReturn;
use App\User;
use Exception;
use Illuminate\Http\Request;

class OrderReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderReturnRequest $request)
    {
        $this->validate($request, [
            'orderItemId' => 'required',
            'reason' => 'required|string|max:255',
            'quantity' => 'required|min:1'
        ]);

        $user = auth()->guard('api')->user();
        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $orderItem = OrderItem::where('id', $request->orderItemId)->firstOrFail();
        if (!$orderItem) {
            return response()->json([
                'data' => ['ticket' => ''],
                'status' => false,
                'message' => "Order item not found.",
                'statusCode' => 200
            ], 200);
        }
        if ($orderItem->orderReturn) {
            return response()->json([
                'data' => ['ticket' => ''],
                'status' => false,
                'message' => "Request has already been submitted.",
                'statusCode' => 200
            ], 200);
        }
        $ticket = 'ORGOGO' . strtoupper(bin2hex(random_bytes(3)));
        $request->merge(['order_item_id' => $request->orderItemId, 'ticket' => $ticket]);
        try {
            $store = OrderReturn::create([
                'ticket' => $ticket,
                'order_item_id' => $request->orderItemId,
                'reason' => $request->reason,
                'quantity' => $request->quantity,
                'order_id' => $orderItem->order_id,
                'user_id' => $orderItem->user_id,
                'vendor_id' => $orderItem->vendor_id,
                'product_id' => $orderItem->product_id,
            ]);
            if ($store) {
                return response()->json([
                    'data' => ['ticket' => $ticket],
                    'status' => true,
                    'message' => "Success",
                    'statusCode' => 200
                ], 200);
            } else {
                return response()->json([
                    'data' => ['ticket' => ''],
                    'status' => false,
                    'message' => "Something Went Wrong",
                    'statusCode' => 200
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'data' => ['ticket' => ''],
                'status' => false,
                'message' => "Server Error",
                'statusCode' => 404
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
