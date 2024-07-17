<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Vendor\CommonController;
use App\Http\Resources\Admin\CompanyResource;
use App\Http\Resources\Vendor\AdminResource;
use App\Services\ProductService;
use App\Services\UserService;

class DashboardController extends CommonController
{
    /**
    * @var UserService
    */
    private $userService;

    /**
    * @var ProductService
    */
    private $productService;
    
    public function __construct(
        UserService $userService,
        ProductService $productService
    ) {
        parent::__construct();
        $this->userService        = $userService;
        $this->productService        = $productService;
    }

    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        $this->website['counts'] = [
            'users'        => $this->userService->getCount(),
            'products'        => $this->productService->getVendorProductCount(),
            'thisMonth'    => [
                'users'        => $this->userService->thisMonthData(),
                'products' => $this->productService->thisMonthVendorProductData()
            ],
        ];

        if (request()->ajax()) {
            return response()->json(['data' => $this->website['counts']]);
        }

        $this->website['company']  = new CompanyResource($this->website['company']);
        $this->website['authUser'] = new AdminResource(auth()->guard('vendor')->user());

        return view('vendor.index', $this->website);
    }
}
