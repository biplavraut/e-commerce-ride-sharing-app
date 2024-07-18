<?php

namespace App\Http\Controllers\Vendor;

use App\Services\UserService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\DineinFormService;
use App\Http\Resources\Vendor\AdminResource;
use App\Http\Resources\Admin\CompanyResource;
use App\Http\Controllers\Vendor\CommonController;

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


    /**
     * @var DineinFormService
     */
    private $dineInService;

    public function __construct(
        UserService $userService,
        ProductService $productService,
        DineinFormService $dineInService,
        OrderService $orderService
    ) {
        parent::__construct();
        $this->userService        = $userService;
        $this->productService        = $productService;
        $this->orderService        = $orderService;
        $this->dineInService = $dineInService;
    }

    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        $this->website['counts'] = [
            'users'        => $this->userService->getCount(),
            'products'        => $this->productService->getVendorProductCount(),
            'orders' => $this->orderService->getVendorOrderCount(),
            'takeawayorders' => $this->orderService->getVendorTakeawayOrderCount(),
            'dinein' => $this->dineInService->query()->where('vendor_id', auth()->id())->where('status', 'pending')->count(),
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
