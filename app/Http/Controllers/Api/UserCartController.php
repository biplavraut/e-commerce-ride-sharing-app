<?php

namespace App\Http\Controllers\Api;

use App\UserCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\CartResources;

class UserCartController extends Controller
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

    public function index()
    {
        return CartResources::collection(UserCart::where(['user_id' => $this->user->id])->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'specialInstruction' => 'nullable|string',
            'preferred_deli_time' => 'nullable|string',
            'preferred_deli_date' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $data = [
            "user_id" => $this->user->id,
            "product_id" => $request->product_id,
            "quantity" => $request->quantity,
            "color" => $request->color,
            "size" => $request->size,
            'date' => $request->preferred_deli_date,
            'time' => $request->preferred_deli_time,
            'special_instruction' => $request->specialInstruction
        ];
        try {
            $usercart = UserCart::create($data);
            return successResponse("Item successfully added to cart.");
        } catch (\Throwable $th) {
            return failureResponse("Error:" . $th->getMessage(), 422, 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserCart  $userCart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'specialInstruction' => 'nullable|string',
            'preferred_deli_time' => 'nullable|string',
            'preferred_deli_date' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $cartDetail = UserCart::where(['product_id' => $request->product_id, 'user_id' => $this->user->id])->first();

        if ($request->quantity == 0) {
            if ($cartDetail->delete()) {
                return successResponse("Cart item removed.");
            } else {
                return failureResponse("Unable to perform action", 422, 422);
            }
        } else {
            $cartDetail->quantity = $request->quantity;
            $cartDetail->size = $request->size;
            $cartDetail->color = $request->color;
            $cartDetail->date = $request->preferred_deli_date;
            $cartDetail->time = $request->preferred_deli_time;
            $cartDetail->special_instruction = $request->specialInstruction;
            if ($cartDetail->save()) {
                return successResponse("Cart item updated.");
            } else {
                return failureResponse("Unable to perform action.", 422, 422);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserCart  $userCart
     * @return \Illuminate\Http\Response
     */
    public function destroy($cartId)
    {
        $findrecord = UserCart::findOrFail($cartId);
        $deleteRecord = $findrecord->delete();
        if ($deleteRecord) {
            return successResponse("Item remove from cart.");
        } else {
            return failureResponse("Unable to perform action.", 422, 422);
        }
    }
}
