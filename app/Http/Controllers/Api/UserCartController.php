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
        return CartResources::collection(UserCart::where(['user_id'=>$this->user->id])->get())->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
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
          ];
          try {
            $usercart = UserCart::create($data);
                return successResponse("Item successfully added to cart");
          } catch (\Throwable $th) {
            return failureResponse("Error:".$th->getMessage(), 422, 422); 
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
        ]);
        
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $cartDetail = UserCart::where(['product_id'=>$request->product_id,'user_id'=>$this->user->id])->first();
        $cartDetail->quantity = $request->quantity;
        if($cartDetail->save()){
            return successResponse("Cart item updated");
        }else{
            return failureResponse("Unable to perform action", 422, 422);
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
        if($deleteRecord){
            return successResponse("Item remove from cart");
        }else{
            return failureResponse("Unable to perform action", 422, 422);
        }
    }
}
