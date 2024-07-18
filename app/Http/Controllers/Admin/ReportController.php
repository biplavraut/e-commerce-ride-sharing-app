<?php

namespace App\Http\Controllers\Admin;

use App\EliteUserRequest;
use App\Trip;
use App\Http\Controllers\Controller;
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
use App\Http\Resources\Admin\RidersRevenueResource;
use App\Http\Resources\Admin\TopUserTransactionResource;
use App\Http\Resources\Admin\TrendingProductResource;
use App\Http\Resources\Admin\UsersRevenueResource;
use App\Http\Resources\Admin\VendorRevenueResource;
use App\Http\Resources\Api\TransactionResource;
use App\Order;
use App\OrderItem;
use App\Referral;
use App\RiderReferral;
use App\Services\DeliveryService;
use App\Services\PaymentLogService;
use App\User;
use App\UserDevice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends CommonController
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
     * @var PaymentLogService
     */
    private $paymentLogService;

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
        DriverService $driverService,
        PaymentLogService $paymentLogService
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
        $this->paymentLogService    = $paymentLogService;
    }
    public function index($param1 = null, $param2 = null, $param3 = null)
    {
        $this->website['counts'] = [
            'users'        => $this->userService->getTodayCount(),
            'categories'   => $this->categoryService->getTodayCount(),
            'productcategories'   => $this->productCategoryService->getTodayCount(),
            'testimonials' => $this->testimonialService->getTodayCount(),
            'news'         => $this->newsService->getTodayCount(),
            'vendors'         => $this->vendorService->getTodayCount(),
            'unverifiedvendors'         => $this->vendorService->getUnverifiedCount(),
            'drivers'         => $this->driverService->getTodayCount(),
            'orders'         => $this->orderService->getAcceptedCount(),
            'activedrivers'         => [$this->driverService->getActiveCount(), $this->driverService->getUnverifiedCount()],
            'products'         => $this->productService->getTodayCount(),
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
            ],
            'thisMonthTopRiders' => [
                'riders'        => $this->ridersMonthIncome(),
            ],
            'thisWeek' => 'Starts' . now()->startOfWeek() . 'Ends' . now()->endOfWeek()
        ];

        if (request()->ajax()) {
            return response()->json(['data' => $this->website['counts']]);
        }
        return $this->website;
    }
    // =========== ****** Start of Report Dashboard ****** ===========
    public function reportDashboard(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $data = [
            'users'        => $this->userService->query()->whereBetween('created_at', [$from, $to])->count(),
            'categories'   => $this->categoryService->query()->whereBetween('created_at', [$from, $to])->count(),
            'productcategories'   => $this->productCategoryService->query()->whereBetween('created_at', [$from, $to])->count(),
            'vendors'         => $this->vendorService->query()->whereBetween('created_at', [$from, $to])->count(),
            'unverifiedvendors'         => $this->vendorService->getUnverifiedCount(),
            'drivers'         => $this->driverService->query()->whereBetween('created_at', [$from, $to])->count(),
            'orders'         => $this->orderService->query()->whereBetween('created_at', [$from, $to])->count(),
            'orders_completed' => round($this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->count()),
            'trips_completed' => round($this->tripService->query()->where('status', 'completed')->whereBetween('created_at', [$from, $to])->count()),
            'order_revenue' => $this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->sum('total'),
            'trip_revenue' => $this->tripService->query()->where('status', 'completed')->whereBetween('created_at', [$from, $to])->sum('price'),
            'trips'         => [$this->tripService->getTripsStatusCount("completed", $from, $to), $this->tripService->getTripsStatusCount("ongoing", $from, $to), $this->tripService->getTripsStatusCount("accident", $from, $to), $this->tripService->getTripsStatusCount("dispute", $from, $to)],
            'activedrivers'         => [$this->driverService->getActiveCount(), $this->driverService->getUnverifiedCount()],
            'products'         => $this->productService->query()->whereBetween('created_at', [$from, $to])->count(),
            'trending_products'  => $this->trendingProducts($from, $to)
        ];
        return response()->json(['data' => $data]);
    }

    // Top products
    public function trendingProducts($from, $to)
    {
        $trending = OrderItem::select(DB::raw('name, sum(quantity) as total_quantity, product_id, vendor_id, avg(price) as avg_price, discount_type, avg(discount) as avg_discount, avg(tax_amt) as avg_tax_amt, avg(service_charge_amt) as avg_service_charge_amt'))
            ->groupBy('product_id')
            ->with('product')
            ->with(array('vendor' => function ($query) {
                $query->select('id', 'business_name');
            }))
            // ->where('status', 'DELIVERED')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('total_quantity', 'DESC')->limit(5)->get();
        // return $trending;
        return TrendingProductResource::collection($trending);
    }
    // =========== ****** End of Report Dashboard ****** ===========
    public function requestFromTo($request)
    {
        switch ($request->q) {
            case 'custom':
                $from = $request->from;
                $to = $request->to;
                break;
            case 'this-month':
                $from = now()->startOfMonth();
                $to =  now()->endOfMonth()->addDay();
                break;
            case 'this-week':
                $from = now()->locale(config('app.locale'))->startOfWeek();
                $to =  now()->locale(config('app.locale'))->endOfWeek();
                break;
            case 'yesterday':
                $from = date('Y-m-d 00-00-00', strtotime("-1 days"));
                $to =  date('Y-m-d 00-00-00');
                break;
            default:
                $from = date('Y-m-d 00-00-00');
                $to =  date('Y-m-d 00-00-00', strtotime("+1 days"));
        }
        return ['from' => $from, 'to' => $to];
    }
    // =========== ****** Start of App Users Report ****** ===========
    public function appUserReport(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $data = [
            'appUserToday' => $this->userService->getTodayCount(),
            'appUserYesterday' => $this->userService->getYesterdayCount(),
            'appUserThisWeek' => $this->userService->getWeekCount(),
            'appUserThisMonth' => $this->userService->getTotalMonthCount(),
            'appUserCustom' => $this->userService->query()
                ->whereBetween('created_at', [$from, $to])->count(),
            'appUserData' => $this->appUserData($from, $to),
            'appUserDevices' => UserDevice::groupBy('user_id')->count(),
            'eliteRequestData' => EliteUserRequest::with(array('user' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'phone', 'verified');
            }))->whereBetween('created_at', [$from, $to])->get()
        ];
        return response()->json(['data' => $data]);
    }

    public function appUserData($from, $to)
    {
        $user_data = $this->userService->query()
            // ->with('tripsYesterday','tripsToday')->with(array('tripsThisWeek' => function($query){
            //     $query->whereBetween('created_at', $this->userService->thisWeek());
            // }))
            ->with(array('trips' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_trips, user_id, sum(price) as trip_revenue,status'))->groupBy('user_id')->where('status', 'completed')->whereBetween('created_at', [$from, $to]);
            }))
            // ->with('ordersYesterday','ordersToday')->with(array('ordersThisWeek' => function($query){
            //     $query->whereBetween('created_at', $this->userService->thisWeek());
            // }))            
            ->with(array('orders' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_orders, user_id, sum(total) as order_revenue,status'))->groupBy('user_id')->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to]);
            }))
            ->whereBetween('created_at', [$from, $to])
            ->get();
        // return $user_data;
        return UsersRevenueResource::collection($user_data);
    }

    // =========== ****** End of App Users Report ****** ===========

    // =========== ****** Start of App Vendor Report ****** ===========
    public function vendorReport(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $data = [
            'dataToday' => $this->vendorService->getTodayCount(),
            'dataYesterday' => $this->vendorService->getYesterdayCount(),
            'dataThisWeek' => $this->vendorService->getWeekCount(),
            'dataThisMonth' => $this->vendorService->getTotalMonthCount(),
            'dataCustom' => $this->vendorService->query()
                ->whereBetween('created_at', [$from, $to])->count(),
            'tableData' => $this->vendorData($from, $to),
            'serviceVendorData' => $this->serviceVendorData($from, $to),
        ];
        return response()->json(['data' => $data]);
    }

    public function vendorData($from, $to)
    {
        $vendor_data = $this->vendorService->query()
            ->with(array('orders' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_orders, vendor_id, sum(total) as order_revenue,status'))->groupBy('vendor_id')->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to]);
            }))
            ->with('services')
            ->whereBetween('created_at', [$from, $to])
            ->get();
        //return $vendor_data;
        return VendorRevenueResource::collection($vendor_data);
    }
    public function serviceVendorData($from, $to)
    {
        return $this->categoryService->query()->select('name')
            ->withCount('registeredVendor')
            // ->with(array('registeredVendor' => function($query) use($from, $to) {
            //     $query->where('from','app');
            // }))
            ->get();
    }
    // =========== ****** End of Vendors Report ****** ===========

    // =========== ****** Start of Rider Report ****** ===========
    public function riderReport(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $data = [
            'riderToday' => $this->driverService->getTodayCount(),
            'riderYesterday' => $this->driverService->getYesterdayCount(),
            'riderThisWeek' => $this->driverService->getWeekCount(),
            'riderThisMonth' => $this->driverService->getTotalMonthCount(),
            'riderCustom' => $this->driverService->query()
                ->whereBetween('created_at', [$from, $to])->count(),
            'riderData' => $this->riderData($from, $to)
        ];
        return response()->json(['data' => $data]);
    }

    public function riderData($from, $to)
    {
        $rider_data = $this->driverService->query()
            ->with(array('completedTrips' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_trips, driver_id, sum(price) as trip_revenue,status'))->groupBy('driver_id')->whereBetween('created_at', [$from, $to]);
            }))
            ->with(array('cancelledTrips' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_trips, driver_id, sum(price) as trip_revenue,status'))->groupBy('driver_id')->whereBetween('created_at', [$from, $to]);
            }))
            ->with(array('completedDeliveries' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_deliveries, driver_id, status'))->groupBy('driver_id')->where('status', 'delivered')->whereBetween('created_at', [$from, $to]);
            }))
            ->whereBetween('created_at', [$from, $to])
            ->get();
        return RidersRevenueResource::collection($rider_data);
    }

    // =========== ****** End of Riders Report ****** ===========

    // =========== ****** Start of Order Report ****** ===========
    public function orderReport(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $data = [
            'orders' => $this->orderService->query()->whereBetween('created_at', [$from, $to])->count(),
            'takeaway' => $this->orderService->query()->where('takeaway', 1)->whereBetween('created_at', [$from, $to])->count(),
            'delivered' => round($this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->count()),
            'settled' => $this->orderService->query()->where('status', 'DELIVERED')->where('settle_status', 1)->where('takeaway', 0)->whereBetween('created_at', [$from, $to])->count(),

            'top_products'  => $this->topProducts($from, $to),
            'top_vendors'  => $this->orderService->query()->select('vendor_id', DB::raw('count(*) as orders, sum(total) as order_total'))
                ->where('status', 'DELIVERED')
                ->groupBy('vendor_id')
                ->with(array('vendor' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'business_name');
                }))->whereBetween('created_at', [$from, $to])->orderBy('order_total', 'desc')->limit(5)->get(),
            'payment_mode' => $this->orderService->query()->select('payment_mode', DB::raw('count(*) as paid_total'))->groupBy('payment_mode')->whereBetween('created_at', [$from, $to])->get(),
            'order_status' => $this->orderService->query()->select('status', DB::raw('count(*) as status_total'))->groupBy('status')->whereBetween('created_at', [$from, $to])->get(),
            'order_markers' => $this->orderService->query()->select('location', 'lat', 'long')->where('status', 'DELIVERED')->where('takeaway', 0)->whereBetween('created_at', [$from, $to])->get(),

            'order_price_total' => $this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->sum('total'),
            'gogo_price_total' => $this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->sum('paying_total'),
            'shipping_fee' => $this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->sum('shipping_fee'),
            'refundable_amount' => $this->orderService->query()->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to])->sum('refundable_amount'),

            'amount_settled' => $this->orderService->query()->where('status', 'DELIVERED')->where('settle_status', 1)->where('takeaway', 0)->whereBetween('created_at', [$from, $to])->sum('paying_total'),
            'amount_to_settle' => $this->orderService->query()->where('status', 'DELIVERED')->where('settle_status', 0)->where('takeaway', 0)->whereBetween('created_at', [$from, $to])->sum('paying_total'),
            'total_settle_amount' => $this->orderService->query()->where('status', 'DELIVERED')->where('takeaway', 0)->whereBetween('created_at', [$from, $to])->sum('paying_total'),

        ];
        return response()->json(['data' => $data]);
    }

    // Top products
    public function topProducts($from, $to)
    {
        $trending = OrderItem::select(DB::raw('name, sum(quantity) as total_quantity, product_id, vendor_id, avg(price) as avg_price, discount_type, avg(discount) as avg_discount, avg(tax_amt) as avg_tax_amt, avg(service_charge_amt) as avg_service_charge_amt'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'DESC')
            ->whereHas('order', function ($query) {
                $query->where('status', 'DELIVERED');
            })
            ->with('product')
            ->with(array('vendor' => function ($query) {
                $query->select('id', 'business_name');
            }))
            // ->where('status', 'DELIVERED')
            ->whereBetween('created_at', [$from, $to])
            ->limit(5)->get();
        // return $trending;
        return TrendingProductResource::collection($trending);
    }
    // Top Vendors
    public function topVendors($from, $to)
    {
        $trending = OrderItem::select(DB::raw('name, sum(quantity) as total_quantity, product_id, vendor_id, avg(price) as avg_price, discount_type, avg(discount) as avg_discount, avg(tax_amt) as avg_tax_amt, avg(service_charge_amt) as avg_service_charge_amt'))
            ->groupBy('product_id')
            ->with('product')
            ->with(array('vendor' => function ($query) {
                $query->select('id', 'business_name');
            }))
            // ->where('status', 'DELIVERED')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('total_quantity', 'DESC')->limit(5)->get();
        // return $trending;
        return TrendingProductResource::collection($trending);
    }
    // =========== ****** End of Order Report ****** ===========

    // =========== ****** Start of Trips Report ****** ===========
    public function tripReport(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $data = [
            'trips' => $this->tripService->query()->whereBetween('created_at', [$from, $to])->count(),
            'top_riders'  => $this->tripService->query()->select('driver_id', DB::raw('count(*) as trips, sum(price) as trip_total'))
                ->where('status', 'completed')
                ->groupBy('driver_id')
                ->orderBy('trips', 'desc')
                ->with(array('driver' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }))
                ->whereBetween('created_at', [$from, $to])->limit(5)->get(),
            'payment_mode' => $this->tripService->query()->select('payment_mode', DB::raw('count(*) as paid_total'))->groupBy('payment_mode')->whereBetween('created_at', [$from, $to])->get(),
            'trips_status' => $this->tripService->query()->select('status', DB::raw('count(*) as status_total'))->groupBy('status')->whereBetween('created_at', [$from, $to])->get(),
            'vehicle_type' => $this->tripService->query()->select('vehicle_type', DB::raw('count(*) as total_vehicles'))->groupBy('vehicle_type')->whereBetween('created_at', [$from, $to])->get(),
            'from_markers' => $this->tripService->query()->select('from', 'from_lat', 'from_long')->where('status', 'completed')->whereBetween('created_at', [$from, $to])->get(),
            'to_markers' => $this->tripService->query()->select('to', 'to_lat', 'to_long')->where('status', 'completed')->whereBetween('created_at', [$from, $to])->get(),
            'trip_price_total' => $this->tripService->query()->where('status', 'completed')->whereBetween('created_at', [$from, $to])->sum('price'),
        ];
        return response()->json(['data' => $data]);
    }
    // =========== ****** End of Trips Report ****** ===========

    // App user Transactions
    public function appUserTransaction(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $user = $this->userService->query()
            ->select('id', 'first_name', 'last_name', 'phone')
            ->where('id', $request->user)->first();
        $logs = $user->transactionHistories()->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'DESC')->get();
        // return Datatables::of($logs)->make(true);
        // of($items)->rawColumns(['items_id','brands_description'])
        $payments = $user->paymentLogs()->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'DESC')->get();
        return TransactionResource::collection($logs)->additional(['user' => $user, 'payments' => $payments]);
    }

    // Top user Transactions
    public function topUserTransaction(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];

        $user = $this->userService->query()
            ->select('id', 'first_name', 'last_name', 'phone', 'created_at')
            ->with(array('trips' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as total_trips, user_id,status'))->groupBy('user_id')->where('status', 'completed')->whereBetween('created_at', [$from, $to]);
            }))
            ->with(array('orders' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(DISTINCT ref_number) as total_orders, user_id,status'))->groupBy('user_id')->where('status', 'DELIVERED')->whereBetween('created_at', [$from, $to]);
            }))
            ->with(array('transactionHistories' => function ($query) use ($from, $to) {
                $query->select(DB::raw('count(*) as utilities, user_id, type'))->groupBy('user_id')->where('type', 'paid')->whereBetween('created_at', [$from, $to]);
            }))
            ->get();
        //return $user;
        return TopUserTransactionResource::collection($user);
    }
    // =========== ****** End of User Transaction Report ****** ===========

    // App User Reffer Report
    public function refferedUser(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $userFromReferred = Referral::whereBetween('created_at', [$from, $to])
            ->with(array('usedBy' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, used_code, created_at'));
            }))
            ->with(array('user' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))
            ->get();
        return response()->json(['referredUser' => $userFromReferred, 'from' => $dates['from'], 'to' => $dates['to']]);
    }
    public function topUserReferrar(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $topAppReferrars = Referral::select(DB::raw('count(*) as total_referred, user_id'))
            ->orderBy('total_referred', 'desc')
            ->with(array('user' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))->groupBy('user_id')->whereBetween('created_at', [$from, $to])->limit(10)->get();
        return response()->json(['topAppReferrars' => $topAppReferrars, 'from' => $dates['from'], 'to' => $dates['to']]);
    }

    // Rider Reffer Report
    public function refferedRider(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $riderFromReferred = RiderReferral::whereBetween('created_at', [$from, $to])
            ->with(array('usedBy' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, used_code, created_at'));
            }))
            ->with(array('driver' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))
            ->get();

        return response()->json(['riderFromReferred' => $riderFromReferred, 'from' => $dates['from'], 'to' => $dates['to']]);
    }
    public function topRiderReferrar(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $topRiderReferrars = RiderReferral::select(DB::raw('count(*) as total_referred, driver_id'))
            ->orderBy('total_referred', 'desc')
            ->with(array('driver' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))->groupBy('driver_id')->whereBetween('created_at', [$from, $to])->limit(10)->get();
        return response()->json(['topRiderReferrars' => $topRiderReferrars, 'from' => $dates['from'], 'to' => $dates['to']]);
    }

    public function refferReport(Request $request)
    {
        $dates = $this->requestFromTo($request);
        $from = $dates['from'];
        $to = $dates['to'];
        $userFromReferred = Referral::whereBetween('created_at', [$from, $to])
            ->with(array('usedBy' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, used_code, created_at'));
            }))
            ->with(array('user' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))
            ->get();
        $topAppReferrars = Referral::select(DB::raw('count(*) as total_referred, user_id'))
            ->orderBy('total_referred', 'desc')
            ->with(array('user' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))->groupBy('user_id')->whereBetween('created_at', [$from, $to])->limit(10)->get();

        $riderFromReferred = RiderReferral::whereBetween('created_at', [$from, $to])
            ->with(array('usedBy' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, used_code, created_at'));
            }))
            ->with(array('driver' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))
            ->get();
        $topRiderReferrars = RiderReferral::select(DB::raw('count(*) as total_referred, driver_id'))
            ->orderBy('total_referred', 'desc')
            ->with(array('driver' => function ($query) {
                $query->select(DB::raw('id,first_name,last_name, phone, refer_code, created_at'));
            }))->groupBy('driver_id')->whereBetween('created_at', [$from, $to])->limit(10)->get();
        // return $userFromReferred;
        return response()->json(['referredUser' => $userFromReferred, 'topAppReferrars' => $topAppReferrars, 'riderFromReferred' => $riderFromReferred, 'topRiderReferrars' => $topRiderReferrars, 'from' => $dates['from'], 'to' => $dates['to']]);
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
    public function ridersMonthIncome()
    {
        // $trips = $this->tripService->query()->where('status', 'completed')->WhereDate('created_at', date('Y-m-d'))->with('driver')->get();
        $trips = Trip::select(DB::raw('count(*) as total, driver_id, sum(price) as price'))
            ->groupBy('driver_id')
            ->where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()->addDay()])
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
