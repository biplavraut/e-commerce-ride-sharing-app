<?php

namespace App\Http\Controllers\Admin;

use App\Trip;
use App\Delivery;
use App\DefaultConf;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\TripService;
use App\Services\UserService;
use App\Services\OrderService;
use App\Services\DriverService;
use App\Services\VendorService;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\DeliveryService;
use Illuminate\Support\Facades\DB;
use App\Services\TestimonialService;
use App\Services\OrderFeedbackService;
use App\Services\ProductCategoryService;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\Admin\CompanyResource;
use App\Http\Resources\Admin\DefaultConfResource;
use App\Http\Resources\Admin\RiderIncomeResource;
use App\Services\AdditionalService;
use App\Services\DineinFormService;
use App\Services\DonationService;
use App\Services\OrderReturnService;

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

    /**
     * @var OrderFeedbackService
     */
    private $orderFeedbackService;


    /**
     * @var DineinFormService
     */
    private $dineInService;


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
        OrderFeedbackService $orderFeedbackService,
        OrderReturnService $orderReturnService,
        DriverService $driverService,
        DineinFormService $dineInService,
        DonationService $donationService,
        AdditionalService $additionalService
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
        $this->orderFeedbackService        = $orderFeedbackService;
        $this->orderReturnService = $orderReturnService;
        $this->dineInService        = $dineInService;
        $this->donationService = $donationService;
        $this->additionalService = $additionalService;
    }

    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        auth()->guard('admin')->user()->update(['last_login_at' => now()]);
        $this->website['counts'] = [
            'users'        => $this->userService->getCount(),
            'categories'   => $this->categoryService->query()->where('enabled', 1)->count(),
            'productcategories'   => $this->productCategoryService->getCount(),
            'testimonials' => $this->testimonialService->getCount(),
            'news'         => $this->newsService->getCount(),
            'vendors'         => $this->vendorService->getCount(),
            'unverifiedvendors'         => $this->vendorService->getUnverifiedCount(),
            'drivers'         => $this->driverService->getRiderCount(),
            'orders'         => $this->orderService->getAcceptedCount(),
            'activedrivers'         => [$this->driverService->getActiveCount(), $this->driverService->getUnverifiedRiderCount()],
            'products'         => $this->productService->getCount(),
            'unverifiedproducts'         => $this->productService->getUnverifiedCount(),
            'trips'         => [$this->tripService->getStatusCount("completed"), $this->tripService->getOnGoingCount(), $this->tripService->getStatusCount("accident"), $this->tripService->getStatusCount("dispute"), $this->tripService->query()->count()],
            'deliveries'         => [$this->deliveryService->getCompletedCount(), $this->deliveryService->getOnGoingCount()],
            'orderFeedbacks'         => $this->orderFeedbackService->query()->whereNull('respond')->count(),
            'orderReturns'         => $this->orderReturnService->query()->where('status', '!=', 'resolved')->count(),
            'dinein'         => $this->dineInService->query()->where('status', '!=', 'completed')->count(),
            'donations' => $this->donationService->getCount(),
            'totalDonation' => $this->donationService->query()->sum('donation'),
            'additionalServices' => $this->additionalService->getCount(),
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
            ],
            'today' => date('Y-m-d 00-00-00'),
            'today_end' => date('Y-m-d 00-00-00', strtotime("+1 days")),
            'yesterday' => date('Y-m-d 00-00-00', strtotime("-1 days")),
            'thisweek' => $this->userService->thisWeek(),
            'thismonth' => $this->userService->thisMonth(),
            'a_month_period' => date('Y-m-d 00-00-00', strtotime("-30 days")),
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
        $trips = Trip::select(DB::raw('count(*) as total, driver_id, sum(price) as price'))
            ->groupBy('driver_id')
            ->where('status', 'completed')
            ->WhereDate('created_at', date('Y-m-d'))
            ->whereHas('driver')
            ->with('driver')
            ->orderBy('price', 'DESC')
            ->get();
        // return $trips;
        return RiderIncomeResource::collection($trips);



        //         ->groupBy('state_id','locality')
        //   ->havingRaw('count > 1 ')
        //   ->having('items.name','LIKE',"%$keyword%")
        //   ->orHavingRaw('brand LIKE ?',array("%$keyword%"))
    }

    public function globalConf()
    {
        return new DefaultConfResource(DefaultConf::first());
    }
}
