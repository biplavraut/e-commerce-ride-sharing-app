<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Wishlist;
use App\Product;
use App\Http\Resources\Api\ProductResource;


class WishlistController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->user = auth()->guard('api')->user();
        if (!$this->user) {
            return failureResponse("Token Expired.", 401, 401);
        }
    }

    public function getlist()
    {
        $allWishListProduct = Wishlist::where("user_id", $this->user->id)->orderBy("created_at", "desc")->pluck("product_id");
        if ($allWishListProduct->count() > 0) {
            $allProducts = Product::whereIn("id", $allWishListProduct)->where('hide', 0)->where('verified', 1)->paginate(20);
            return ProductResource::collection($allProducts)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } else {
            return response()->json(['data' => [], 'status' => true, 'message' => "", 'statusCode' => 200], 200);
        }
    }

    public function addToWishlist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $getProductDetails = Product::findOrFail($request->product_id);
        Wishlist::where(['user_id' => $this->user->id, 'product_id' => $getProductDetails->id])->delete();
        $addRequest = Wishlist::create(['user_id' => $this->user->id, 'product_id' => $getProductDetails->id]);
        if ($addRequest) {
            return successResponse("Item successfully added to wishlist");
        } else {
            return failureResponse("Unable to perform your action", 418, 418);
        }
    }

    public function removeFromWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $getProductDetails = Product::findOrFail($request->product_id);
        $delRequest = Wishlist::where("product_id", $request->product_id)->delete();
        if ($delRequest) {
            return successResponse("Item successfully removed from wishlist");
        } else {
            return failureResponse("Unable to perform your action", 418, 418);
        }
    }
}
