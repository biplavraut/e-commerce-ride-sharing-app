<?php

namespace App\Http\Controllers\Api;

use App\DealProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Http\Resources\Api\DealProductResource;
use App\Http\Resources\Api\ServiceDealResource;
use App\Services\DealService;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DealController extends CommonController
{
    /**
     * @var DealService
     */
    private $dealService;
    /**
     * @var ProductService
     */
    private $productService;


    public function __construct(DealService $dealService, ProductService $productService)
    {
        parent::__construct();
        $this->dealService = $dealService;
        $this->productService = $productService;
    }

    public function activeDeals($serviceId)
    {
        try {
            $deals = $this->dealService->query()
                ->where('category_id', $serviceId)
                ->where('status', 1)
                ->where('from', '<=', date('Y-m-d H:i:s'))
                ->where('to', '>', date('Y-m-d H:i:s'))
                ->with(array('dealproducts' => function ($query) {
                    $query->with('product');
                }))
                ->get();
            // return $deals;
            return (ServiceDealResource::collection($deals))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Deals Not Found.", 404, 404);
        }
    }

    public function dealProducts($dealId)
    {
        try {
            $deal_products = DealProduct::where('deal_id', $dealId)
                ->with('product')->get();
            return (DealProductResource::collection($deal_products))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        } catch (\Throwable $th) {
            return failureResponse("Deal Product Not Found.", 404, 404);
        }
    }
}
