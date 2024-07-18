<?php

namespace App\Http\Controllers\Admin;

use App\RefundLog;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\VendorService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RefundResource;
use App\Http\Resources\Admin\SettlementReportResource;
use App\Http\Resources\Admin\VendorAdvanceSettlementResource;
use App\Http\Resources\Admin\VendorSettleResource;
use App\Order;
use App\VendorAdvanceSettlement;
use App\VendorSettleLog;
use Exception;
use Illuminate\Support\Facades\DB;

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
        $refundOrders = $this->orderService->query()->where('payment_mode', '!=', 'cash on delivery')->where('refundable_amount', '>', 0)->oldest()->get();
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

    public function vendorList($time)
    {
        \Log::channel('notification')->info("Settlement Logs ----" . $time);

        switch ($time) {
            case '30-days':
                $settlement_time = '30';
                break;
            case '15-days':
                $settlement_time = '15';
                break;
            default:
                $settlement_time = '7';
                break;
        }
        \Log::channel('notification')->info("Settlement time ----" . $settlement_time);

        // For every order check if settled ??
        // Get vendor Id
        try {
            $vendor_to_settle = $this->vendorService->query()
                ->whereHas('orders', function ($q) {
                    $q->where('status', 'DELIVERED')->where('settle_status', 0);
                })
                ->with(array('orders' => function ($query) {
                    $query->select(DB::raw('count(*) as total_orders, vendor_id, sum(total) as total_amount, sum(paying_total) as total_to_pay, settle_status'))
                        ->where('status', 'DELIVERED')->where('settle_status', 0)->groupBy('vendor_id');
                }))
                ->where('settlement_time', $settlement_time)
                ->get();
            \Log::channel('notification')->info("vendor to settle ----" . count($vendor_to_settle));
            //return $vendor_to_settle;
            return VendorSettleResource::collection($vendor_to_settle);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function vendorUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $admin_id = auth()->user()->id;
            $settle = VendorSettleLog::create(['vendor_id' => $request->vendor_id, 'amount' => $request->amount, 'log' => $request->remarks, 'admin_id' => $admin_id]);
            if ($settle) {
                $vendor_orders_to_settle = $this->orderService->query()
                    ->select('id')
                    ->where('vendor_id', $request->vendor_id)
                    ->where('status', 'DELIVERED')
                    ->where('settle_status', 0)->pluck('id');
                $updating_order = $this->updateSettledOrders($vendor_orders_to_settle, $settle->id, $admin_id);
                if ($updating_order) {
                    // Updating Advance Settlement amount
                    // If settlement amount is greater then advance total amount ??
                    //  Make all the advance payment status inactive
                    $db_vendor_vendor_list = VendorAdvanceSettlement::where('vendor_id', $request->vendor_id)->where('status', 0);
                    if ($request->vendor_adv_amount > 0) {
                        if ($request->amount >= $request->vendor_adv_amount) {
                            // Update all advance payment status to 1;
                            $db_vendor_vendor_list->update(['status' => 1, 'settled_amount' => DB::raw("amount")]);
                        } else {
                            // settlement amount is less then adv amount
                            // check for each advance paid row and settle it 
                            $amount_to_settle = $request->amount;
                            $listed_vendors = $db_vendor_vendor_list->get();
                            foreach ($listed_vendors as $vendor_advance) {
                                $total_rem_amt_to_settle = $vendor_advance->amount - $vendor_advance->settled_amount;            // Remaining Balance from advance payment
                                if ($amount_to_settle < $total_rem_amt_to_settle) {                                             // Remaining balance is greater 
                                    VendorAdvanceSettlement::where('id', $vendor_advance->id)->update(['status' => 0, 'settled_amount' => $vendor_advance->settled_amount + $amount_to_settle]);
                                    break;
                                    // End the loop and proceed
                                } elseif ($amount_to_settle == $total_rem_amt_to_settle) {
                                    VendorAdvanceSettlement::where('id', $vendor_advance->id)->update(['status' => 1, 'settled_amount' => $vendor_advance->settled_amount + $amount_to_settle]);
                                    break;
                                } else { // Remaining Balance is less then amount to settle
                                    VendorAdvanceSettlement::where('id', $vendor_advance->id)->update(['status' => 1, 'settled_amount' => $vendor_advance->amount]);
                                    $amount_to_settle -= $vendor_advance->amount;
                                    if ($amount_to_settle <= 0) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    DB::commit();
                    // Advance settlement Log settled
                    return response()->json(['status' => true, 'message' => 'Successfully Settled.']);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    protected function updateSettledOrders($vendor_orders_to_settle, $settle_id, $admin_id)
    {
        return Order::whereIn('id', $vendor_orders_to_settle)->update(['settle_status' => 1, 'settlement_date' => date('Y-m-d H:i:s'), 'settle_id' => $settle_id, 'settle_by' => $admin_id]);
    }

    public function listAdvanceSettlement()
    {
        $advance_settlements = $this->vendorService->query()->whereHas('vendorAdvanceSettlement')->get();
        // return $advance_settlements;
        return VendorAdvanceSettlementResource::collection($advance_settlements);
    }

    public function addVendorAdvanceSettlement(Request $request)
    {
        $admin_id = auth()->user()->id;
        try {
            VendorAdvanceSettlement::create(['vendor_id' => $request->vendor_id,  'amount' => $request->amount, 'remarks' => $request->remarks, 'admin_id' => $admin_id]);
            return response()->json(['status' => true, 'message' => 'Successfully Added Fund.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e]);
        }
    }

    // Vendor Settled List
    public function listVendorSettled(Request $request)
    {
        switch ($request->filter) {
            case 'custom':
                $from = $request->from;
                $to = $request->to;
                break;
            case 'month':
                $from = now()->startOfMonth();
                $to =  now()->endOfMonth()->addDay();
                break;
            case 'this-week':
                $from = now()->startOfWeek();
                $to =  now()->startOfWeek()->addDay();
                break;
            case 'yesterday':
                $from = date('Y-m-d 00-00-00', strtotime("-1 days"));
                $to =  date('Y-m-d 00-00-00');
                break;
            default:
                $from = date('Y-m-d 00-00-00');
                $to =  date('Y-m-d 00-00-00', strtotime("+1 days"));
        }
        switch ($request->days) {
            case '30-days':
                $settlement_time = '30';
                break;
            case '15-days':
                $settlement_time = '15';
                break;
            default:
                $settlement_time = '7';
                break;
        }
        // For every order check if settled ??
        // Get vendor Id
        $settled_vendor = VendorSettleLog::select()
            ->whereHas('vendor', function ($q) use ($settlement_time) {
                $q->where('settlement_time', $settlement_time);
            })
            ->with('vendor')
            ->with('admins')
            ->whereBetween('created_at', [$from, $to])
            ->get();
        // return $settled_vendor;
        return SettlementReportResource::collection($settled_vendor);
    }
}
