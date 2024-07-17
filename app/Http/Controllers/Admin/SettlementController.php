<?php

namespace App\Http\Controllers\Admin;

use App\RefundLog;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\VendorService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RefundResource;
use App\Http\Resources\Admin\VendorSettleResource;
use App\VendorSettleLog;

class SettlementController extends CommonController
{
    /** @var VendorService */
    private $vendorService;

    /** @var OrderService */
    private $orderService;


    public function __construct(VendorService $vendorService, OrderService $orderService)
    {
        parent::__construct();
        $this->vendorService = $vendorService;
        $this->orderService = $orderService;
    }

    public function refundList()
    {
        $refundOrders = $this->orderService->query()->where('refundable_amount', '>', 0)->oldest()->get();
        return RefundResource::collection($refundOrders);
    }

    public function refundUpdate(Request $request)
    {
        $order = $this->orderService->query()->where('id', $request->order)->first();
        if ($order->refundable_amount > 0) {
            $log = RefundLog::create(['order_id' => $order->id, 'user_id' => $order->user->id, 'log' => $request->log, 'amount' => $order->refundable_amount]);
            $order->update(['refundable_amount' => 0]);
        }

        return response()->json(['status' => true, 'message' => 'Successfully settled.']);
    }

    public function vendorList()
    {
        $vendors = $this->vendorService->query()
            ->whereHas('orders')
            ->join('orders', 'vendors.id', 'orders.vendor_id')
            ->where('orders.status', 'DELIVERED')
            // ->whereBetween('orders.created_at', [$from, $to])
            ->select('vendors.id', 'vendors.business_name', 'vendors.first_name', 'vendors.last_name', 'vendors.address', 'vendors.phone', 'vendors.email')
            ->distinct('id')
            ->get();

        return VendorSettleResource::collection($vendors);
    }

    public function vendorUpdate(Request $request)
    {
        $vendor = $this->vendorService->query()->where('id', $request->vendor)->first();
        $total = $vendor->orderTotalCOD() + $vendor->orderTotalDIGITAL();
        if ($total > 0) {
            $log = VendorSettleLog::create(['vendor_id' => $request->vendor,  'log' => $request->log, 'amount' => $total]);
        }

        return response()->json(['status' => true, 'message' => 'Successfully settled.']);
    }
}
