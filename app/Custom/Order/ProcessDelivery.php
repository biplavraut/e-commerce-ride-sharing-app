<?php

namespace App\Custom\Order;

use App\Custom\Payment\gogo\gogoWallet;
use App\DefaultConf;
use App\InHouseRiderPaymentLog;
use App\Order;
use App\OrderAdditionalDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class ProcessDelivery
{
    public function __construct($order, $user, $delivery, $driver)
    {
        $this->order = $order;
        $this->user = $user;
        $this->delivery = $delivery;
        $this->driver = $driver;
        $this->additionalDetail = OrderAdditionalDetail::query()->where('order_ref_number', $this->order->ref_number)->first();
    }

    public function completeDelivery()
    {
        try {
            DB::beginTransaction();
            $myOrders = new Order;
            $isFinalDelivery = $this->checkOrderStatus();
            if ($isFinalDelivery) {
                // Update Total Delivered Amount
                $this->additionalDetail->update(['status' => 'COMPLETED', 'total_delivered' => $this->order->total]);
                $cashback = $this->additionalDetail->order_cashback;
                // Process Cashback
                if ($cashback > 0) {
                    // Check Cashback
                    $checkCashback = new CashBack($this->additionalDetail->order_total);
                    $processCashback = $checkCashback->processCashback($this->user->id, $cashback);
                    if (!$processCashback === true) {
                        return $processCashback;
                    }
                }
            } else {
                // Update total deivered amount
                $this->additionalDetail->update(['total_delivered' => $this->order->total]);
            }

            if ($this->delivery !== false) {
                $status = $this->order->payment_mode == "cash on delivery" ? 'delivered' : 'completed';
                $this->delivery->update(['status' => $status, 'delivered_at' => now()]);
            }
            if ($this->driver !== false) {
                $this->driver->status()->update(['status' => 'active']);
            }
            $this->order->update(['status' => 'DELIVERED', 'date' => now()]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function deliveryCollection($type)
    {
        $orderGrandTotal = $this->additionalDetail->order_total;
        $totalCollected = $this->additionalDetail->total_collected ?? 0;
        $totalReceivable = $orderGrandTotal - $totalCollected;
        $orderTotal = $this->order->total;
        switch ($type) {
            case 'total':
                $this->additionalDetail->update(['total_collected' => $totalCollected + $totalReceivable]);
                $log = InHouseRiderPaymentLog::create(['order_id' => $this->order->id, 'driver_id' => $this->driver->id, 'total' => $totalReceivable]);
                return true;
                break;
            default:
                $remainginReceiveable = $orderGrandTotal - $totalCollected;
                if ($remainginReceiveable < $orderTotal) {
                    $this->additionalDetail->update(['total_collected' => $totalCollected + $totalReceivable]);
                    $log = InHouseRiderPaymentLog::create(['order_id' => $this->order->id, 'driver_id' => $this->driver->id, 'total' => $remainginReceiveable]);
                    return true;
                } else {
                    $isFinalDelivery = $this->checkOrderStatus();
                    if ($isFinalDelivery) {
                        $this->additionalDetail->update(['total_collected' => $totalCollected + $totalReceivable]);
                        $log = InHouseRiderPaymentLog::create(['order_id' => $this->order->id, 'driver_id' => $this->driver->id, 'total' => $totalReceivable]);
                        return true;
                    } else {
                        $this->additionalDetail->update(['total_collected' => $totalCollected + $orderTotal]);
                        $log = InHouseRiderPaymentLog::create(['order_id' => $this->order->id, 'driver_id' => $this->driver->id, 'total' => $orderTotal]);
                        return true;
                    }
                }
                return false;
                break;
        }
    }

    public function checkOrderStatus()
    {
        $activeOrders = Order::query()->where([
            ['ref_number', $this->order->ref_number],
            ['id', '!=', $this->order->id],
            ['status', '!=', 'CANCELLED'],
            ['status', '!=', 'DELIVERED'],
        ])->count();
        if ($activeOrders > 0) {
            return false;
        } else {
            return true;
        }
        // $data = [
        //     'cancelledOrder' => $myOrders->where('status', '!=', 'CANCELLED')->count(),
        //     'pendingOrder' => $myOrders->where('status', '!=', 'PENDING')->count(),
        //     'deliveredOrder' => $myOrders->where('status', '!=', 'DELIVERED')->count(),
        //     'pickingUpOrder' => $myOrders->where('status', '!=', 'PICKING UP BY RIDER')->count(),
        //     'assignedOrder' => $myOrders->where('status', '!=', 'ASSIGNED TO RIDER')->count(),
        //     'confirmedOrder' => $myOrders->where('status', '!=', 'CONFIRMED')->count(),
        // ];
        // return $data;
    }
}
