<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Send;
use App\SendItems;
use App\Discount;
use App\Http\Resources\Api\SendItemsResource;
use App\Http\Resources\Api\SendResource;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SendRequest;
use Carbon\Carbon;

class SendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $user;

    public function __construct()
    {
        $this->user = auth()->guard('api')->user();
        if (!$this->user) {
            return failureResponse("Token Expired.", 401, 401);
        }
    }

    public function getItems()
    {
        return SendItemsResource::collection(SendItems::where(['status' => 1])->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setSends(SendRequest $request)
    {
        $user = auth()->guard('api')->user();
        $request->request->add(['user_id' => $this->user->id]);
        $request->request->add(['status' => 1]);
        //Price Calcuation of send order
        $requestedItemDetails = SendItems::findOrFail($request->delivery_item_type);
        $flatPrice = $requestedItemDetails->flat_price;
        $perKMAddedPrice = $request->distance_in_km * $requestedItemDetails->added_per_km_price;
        $weightAddedPrice = $request->delivery_item_weight * $requestedItemDetails->added_weightprice_per_kg;
        $discountDetails = Discount::where("status", 1)->whereDate('applied_till', '>', Carbon::now())->first();
        $totalDiscountAmount = 0;
        $discountMethod = "";
        $discountValue = 0;
        if (!empty($discountDetails) && $discountDetails->discount_value > 0) {
            if ($discountDetails->discount_type == "flat") {
                $totalDiscountAmount = $discountDetails->discount_value;
                $discountMethod = "Flat Discount";
                $discountValue = $discountDetails->discount_value;
            } else {
                $totalDiscountAmount = (int)($flatPrice * $discountDetails->discount_value) / 100;
                $discountMethod = "Percentage Discount";
                $discountValue = $discountDetails->discount_value;
            }
        }
        $finalPriceDetails = [
            "totalPrice" => round($flatPrice + $perKMAddedPrice + $weightAddedPrice, 0),
            "totalPriceAfterDiscount" => round($flatPrice + $perKMAddedPrice + $weightAddedPrice - $totalDiscountAmount, 0),
            "flatPrice" => round($flatPrice),
            "distancePrice" => round($perKMAddedPrice, 0),
            "weightPrice" => round($weightAddedPrice, 0),
            "discountAmount" => round($totalDiscountAmount, 0),
            "discountMethod" => $discountMethod,
            "discountValue" => $discountValue
        ];
        $allRequest = $request->toArray();
        if ($request->confirm_order == 0) {
            return response()->json([
                'data' => $finalPriceDetails,
                'message' => 'Order not confirmed yet',
                'status' => true,
                'statusCode' => 200
            ], 200);
        } else if ($request->confirm_order == 1) {
            $allRequest['user_id'] = $this->user->id;
            $allRequest['status'] = 1;
            $allRequest['extra_column'] = serialize($finalPriceDetails);
            $saveRecord = Send::create($allRequest);
            if ($saveRecord) {
                return successResponse("Send request successfully created");
            } else {
                return failureResponse("Unable to process your request", 422, 422);
            }
        }
    }

    public function listMySendOrder()
    {
        $user = auth()->guard('api')->user();
        $listData = Send::where(['status' => 1, 'user_id' => $this->user->id])->get();
        return SendResource::collection($listData)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Send  $send
     * @return \Illuminate\Http\Response
     */
    public function show(Send $send)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Send  $send
     * @return \Illuminate\Http\Response
     */
    public function edit(Send $send)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Send  $send
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Send $send)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Send  $send
     * @return \Illuminate\Http\Response
     */
    public function destroy(Send $send)
    {
        //
    }
}
