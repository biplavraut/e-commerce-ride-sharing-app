<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductResource;
use App\Services\ProductCategoryService;
use App\Services\ProductService;
use App\Services\VendorService;
use Illuminate\Http\Request;

class ProductController extends CommonController
{
    /**
     * @var VendorService
     */
    private $vendorService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(VendorService $vendorService, ProductService $productService)
    {
        $this->vendorService = $vendorService;
        $this->productService = $productService;
    }

    public function vendorProducts(Request $request)
    {
        try {
            $vendor = $this->vendorService->with('products')->findOrFail($request->vendorId);
            return ProductResource::collection($vendor->products()->where('hide', 0)->where('verified', 1)->paginate(25))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Vendor Not Found.", 200, 200);
        }
    }

    public function getProduct(Request $request)
    {
        $user = auth()->guard('api')->user();
        try {
            $product = $this->productService->query()->where('hide', 0)->Where('verified', 1)->with(['vendor', 'category', 'qas', 'reviews'])->whereHas('vendor', function ($query){
                $query->where('status', 1);
           })->findOrFail($request->id);
            $product->userId = $user ?  $user->id : null;
            $request->request->add(['iam' => $user ?  $user->id : null]);
            return (new ProductResource($product))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Product Not Found.", 404, 404);
        }
    }

    public function searchProduct(Request $request)
    {
        try {
            $keyword = $request->only('query')['query'];

            if (!$keyword) {
                return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
            }
            $results = $this->productService->searchProduct($keyword);
            return (ProductResource::collection($results))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        }
    }
}
