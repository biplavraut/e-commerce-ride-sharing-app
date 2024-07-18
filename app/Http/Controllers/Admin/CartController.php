<?php

namespace App\Http\Controllers\Admin;

use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CartResource;
use App\Http\Resources\Admin\ProductResource;
use App\Services\ProductService;
use App\User;
use App\UserCart;
use Illuminate\Http\Request;

class CartController extends CommonController
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService        = $productService;
    }
    //
    public function loadCart(Request $request)
    {
        $user = User::findOrFail($request->id);
        return CartResource::collection(UserCart::where(['user_id' => $user->id])->paginate(10));
    }

    public function findProduct(Request $request)
    {
        $product = $this->productService->query()
            ->where('title', 'LIKE', '%' . $request->q . '%')
            ->where('hide', 0)->Where('verified', 1)->limit(5)->get();
        return ProductResource::collection($product);
    }

    public function addProduct(Request $request)
    {
        try {
            $usercart = UserCart::create($request->all());
            return successResponse("Item successfully added to cart.");
        } catch (\Throwable $th) {
            return failureResponse("Error:" . $th->getMessage(), 422, 422);
        }
    }

    public function deleteCartProduct(Request $request)
    {
        $findrecord = UserCart::findOrFail($request->cartId);
        $deleteRecord = $findrecord->delete();
        if ($deleteRecord) {
            return successResponse("Item remove from cart.");
        } else {
            return failureResponse("Unable to perform action.", 422, 422);
        }
    }
    public function notifyUser(Request $request)
    {
        $user = User::findOrFail($request->userId);
        $notification = new PushNotification(
            $user->devices->pluck('device_token')->toArray(),
            [
                'title' => 'Cart Updated',
                'message' => 'Product has been added in your cart. Please check you cart and proceed to checkout.',
                'type' => 'cart-added',
            ]
        );
        $notification->send();
        $user->myNotifications()->create(['title' => 'Cart Updated', 'message' => 'Product has been added in your cart. Please check you cart and proceed to checkout.', 'type' => 'cart-added']);
    }
}
