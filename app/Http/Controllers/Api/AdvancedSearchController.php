<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\VendorService;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SearchVendorResource;
use App\Http\Resources\Api\SearchProductResource;

class AdvancedSearchController extends CommonController
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

    public function index(Request $request)
    {
        try {
            $keyword = $request->only('query')['query'];

            if (!$keyword) {
                return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
            }

            if (strlen($keyword) == 0) {
                return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
            }

            $products = $this->productService->query()
                ->where('title', 'LIKE', '%' . $keyword . '%')
                ->where('hide', 0)
                ->where('verified', 1)
                // ->whereHas('vendor')
                ->whereHas('vendor', function ($query) {
                    $query->where('status', 1);
                })
                ->take($this->paginationLimit)
                ->get();

            $vendors = $this->vendorService->query()
                ->where('business_name', 'LIKE', '%' . $keyword . '%')
                ->where('is_hidden', 0)
                ->where('status', 1)
                ->where('verified', 1)
                ->whereHas('products', function ($query) {
                    $query->where('hide', 0)->where('verified', 1);
                })
                ->take($this->paginationLimit)
                ->get();


            return response()->json(['data' => [
                'products' => SearchProductResource::collection($products),
                'vendors' => SearchVendorResource::collection($vendors),
            ], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        }
    }

    public function vendorList(Request $request)
    {
        $keyword = $request->only('query')['query'];

        if (!$keyword) {
            return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        }

        if (strlen($keyword) == 0) {
            return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        }

        $vendors = $this->vendorService->query()
            ->where('business_name', 'LIKE', '%' . $keyword . '%')
            ->where('is_hidden', 0)
            ->where('status', 1)
            ->where('verified', 1)
            ->whereHas('products', function ($query) {
                $query->where('hide', 0)->where('verified', 1);
            })
            ->paginate($this->paginationLimit)
            ->appends($request->query());

        return (SearchVendorResource::collection($vendors))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }


    public function productList(Request $request)
    {

        $keyword = $request->only('query')['query'];

        if (!$keyword) {
            return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        }

        if (strlen($keyword) == 0) {
            return response()->json(['data' => [], 'status' => true, 'message' => "success", 'statusCode' => 200], 200);
        }

        $products = $this->productService->query()
            ->where('title', 'LIKE', '%' . $keyword . '%')
            ->where('hide', 0)
            ->where('verified', 1)
            // ->whereHas('vendor')
            ->whereHas('vendor', function ($query) {
                $query->where('status', 1);
            })
            ->paginate($this->paginationLimit)
            ->appends($request->query());

        return (SearchProductResource::collection($products))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
