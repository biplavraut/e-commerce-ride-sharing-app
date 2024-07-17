<?php

namespace App\Http\Controllers\Admin;

use App\Trip;
use App\Delivery;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\TripService;
use App\Services\UserService;
use App\Services\OrderService;
use App\Services\DriverService;
use App\Services\VendorService;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use App\Services\TestimonialService;
use App\Services\ProductCategoryService;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\Admin\CompanyResource;
use App\Http\Resources\Admin\RiderIncomeResource;
use App\Services\DeliveryService;

class DashboardController extends CommonController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var ProductCategoryService
     */
    private $productCategoryService;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var TestimonialService
     */
    private $testimonialService;
    /**
     * @var NewsService
     */
    private $newsService;

    /**
     * @var VendorService
     */
    private $vendorService;

    /**
     * @var DriverService
     */
    private $driverService;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var TripService
     */
    private $tripService;

    /**
     * @var DeliveryService
     */
    private $deliveryService;

    public function __construct(
        CategoryService $categoryService,
        UserService $userService,
        TestimonialService $testimonialService,
        NewsService $newsService,
        ProductCategoryService $productCategoryService,
        VendorService $vendorService,
        ProductService $productService,
        OrderService $orderService,
        TripService $tripService,
        DeliveryService $deliveryService,
        DriverService $driverService
    ) {
        parent::__construct();
        $this->categoryService    = $categoryService;
        $this->userService        = $userService;
        $this->testimonialService = $testimonialService;
        $this->newsService        = $newsService;
        $this->productCategoryService        = $productCategoryService;
        $this->vendorService        = $vendorService;
        $this->driverService        = $driverService;
        $this->productService        = $productService;
        $this->orderService        = $orderService;
        $this->tripService        = $tripService;
        $this->deliveryService        = $deliveryService;
    }

    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        $this->website['counts'] = [
            'users'        => $this->userService->getCount(),
            'categories'   => $this->categoryService->getCount(),
            'productcategories'   => $this->productCategoryService->getCount(),
            'testimonials' => $this->testimonialService->getCount(),
            'news'         => $this->newsService->getCount(),
            'vendors'         => $this->vendorService->getCount(),
            'unverifiedvendors'         => $this->vendorService->getUnverifiedCount(),
            'drivers'         => $this->driverService->getCount(),
            'orders'         => $this->orderService->getAcceptedCount(),
            'activedrivers'         => [$this->driverService->getActiveCount(), $this->driverService->getUnverifiedCount()],
            'products'         => $this->productService->getCount(),
            'unverifiedproducts'         => $this->productService->getUnverifiedCount(),
            'trips'         => [$this->tripService->getStatusCount("completed"), $this->tripService->getOnGoingCount(), $this->tripService->getStatusCount("accident"), $this->tripService->getStatusCount("dispute")],
            'deliveries'         => [$this->deliveryService->getCompletedCount(), $this->deliveryService->getOnGoingCount()],
            'thisMonth'    => [
                'users'        => $this->userService->thisMonthData(),
                'categories'   => $this->categoryService->thisMonthData(),
                'productcategories'   => $this->productCategoryService->thisMonthData(),
                'testimonials' => $this->testimonialService->thisMonthData(),
                'news'         => $this->newsService->thisMonthData(),
                'vendors'         => $this->vendorService->thisMonthData(),
                'drivers'         => $this->driverService->thisMonthData(),
                'products'         => $this->productService->thisMonthData(),
                'orders'         => $this->orderService->thisMonthData(),
            ],
            'thisDay' => [
                'riders'        => $this->ridersIncome(),
            ]
        ];

        if (request()->ajax()) {
            return response()->json(['data' => $this->website['counts']]);
        }

        $this->website['company']  = new CompanyResource($this->website['company']);
        $this->website['authUser'] = new AdminResource(auth()->guard('admin')->user());
        return view('admin.index', $this->website);
    }


    public function ridersIncome()
    {        
        // $trips = $this->tripService->query()->where('status', 'completed')->WhereDate('created_at', date('Y-m-d'))->with('driver')->get();
        $trips = Trip::select(DB::raw('count(*) as total, driver_id, sum(price) as price'))
            ->groupBy('driver_id')
            ->where('status', 'completed')
            ->WhereDate('created_at', date('Y-m-d'))
            ->with('driver')
            ->orderBy('price', 'DESC')
            ->get();

        return RiderIncomeResource::collection($trips);



        //         ->groupBy('state_id','locality')
        //   ->havingRaw('count > 1 ')
        //   ->having('items.name','LIKE',"%$keyword%")
        //   ->orHavingRaw('brand LIKE ?',array("%$keyword%"))
    }
}
