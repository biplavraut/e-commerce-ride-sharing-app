<?php

namespace App\Custom\Order;

use App\Custom\Payment\gogo\gogoWallet;
use App\DefaultConf;
use App\Order;
use App\OrderAdditionalDetail;
use Exception;
use Illuminate\Support\Facades\DB;

class ProcessCancellation
{
    public function __construct($order, $user)
    {
        $this->order = $order;
        $this->user = $user;
        $this->additionalDetail = OrderAdditionalDetail::query()->where('order_ref_number', $this->order->ref_number)->first();
    }

    public function checkCancellation($reason)
    {
        try {
            DB::beginTransaction();

            $this->additionalDetail = OrderAdditionalDetail::query()->where('order_ref_number', $this->order->ref_number)->first();
            $myOrders = new Order;
            $assocOrderSubTotal = $myOrders->query()->where([
                ['ref_number', $this->order->ref_number],
                ['id', '!=', $this->order->id],
                ['status', '!=', 'CANCELLED']
            ])->sum('subtotal');
            $newTotal = ($assocOrderSubTotal) / 100;
            if ($newTotal > 0) {
                $allCancelled = false;
                // Order has other vendor orders which is delivered or pending

                // Check and Calculate shipping
                if ($this->order->takeaway == 1 && $newTotal == 0) {
                    $shippingCharge = 0;
                } else {
                    $shipping = new ShippingCharge($newTotal, $this->order->delivery_location); //Check if shipping applicable
                    $shippingCharge = $shipping->calculateShipping();
                }

                // Check and calculate Coupon
                $couponAmt = $this->additionalDetail->coupon_discount;

                // Redeem Reward
                $redeemReward = $this->additionalDetail->gogo_reward_redeem;
                if ($redeemReward > 0) {
                    $reward = new RedeemReward($newTotal + $shippingCharge - $couponAmt, $this->user);
                    $redeemReward = $reward->refundAndCalculateRedeem($redeemReward);
                }
                // Check and calculate Cashback
                $checkCashback = new CashBack($newTotal + $shippingCharge);
                $cashback = $checkCashback->calculateCashback();

                $donation = $this->additionalDetail->donation;

                $newPaymentReceivable = $newTotal + $shippingCharge - $redeemReward + $donation - $couponAmt;
                $refundableAmount = 0;
                // Payment Verification
                if ($this->order->payment_mode != 'cash on delivery' || $this->additionalDetail->order_total == $this->additionalDetail->total_collected) {
                    // Case: Multiple Vendor order and ePayment including gogoWallet
                    $userPaid = $this->additionalDetail->order_total;
                    if ($newPaymentReceivable > $userPaid) {
                        DB::rollBack();
                        return 'New Payable amount will be greater then paid.'; // User Payble amount will be greater then paid
                    } else {
                        if ($shippingCharge > 0) {
                            $newOrderWithShipping = $myOrders->query()->where([
                                ['ref_number', $this->order->ref_number],
                                ['id', '!=', $this->order->id],
                                ['status', '!=', 'CANCELLED'],
                            ])->first();
                            if ($newOrderWithShipping) {
                                $newOrderWithShipping->shipping_fee = $shippingCharge; //Assigining shipping charge to first of remaining order
                                $newOrderWithShipping->total = $newOrderWithShipping->subtotal + $shippingCharge;
                                $newOrderWithShipping->save();
                            }
                        }
                        $refundableAmount = $userPaid - $newPaymentReceivable;
                        $refund =  $this->ePaymentOrderRefund($this->order->payment_mode, $refundableAmount);
                    }
                } else {
                    // Case: Multiple Order and Cash on Delivery  
                    // Checking if any associated order is delivered and shipping charge has been taken
                    if ($this->additionalDetail->order_total == $this->additionalDetail->total_collected) {
                        DB::rollBack();
                        // Shipping charge will be appiled or shipping charge has not been received
                        return "Please Contact support to cancel the order.";
                    }

                    // Check delivered without shipping
                    $deliveredWithoutShipping = false;
                    // Check if any delivered
                    $anyDelivered = $myOrders->query()->where([
                        ['ref_number', $this->order->ref_number],
                        ['id', '!=', $this->order->id],
                        ['status', 'DELIVERED'],
                    ])->sum('shipping_fee');
                    if ($anyDelivered > 0) {
                        $deliveredWithoutShipping = true;
                    } else {
                        $deliveredWithoutShipping = false;
                    }

                    if ($shippingCharge > 0 && $deliveredWithoutShipping) {
                        $newOrderWithShipping = $myOrders->query()->where([
                            ['ref_number', $this->order->ref_number],
                            ['id', '!=', $this->order->id],
                            ['status', '!=', 'CANCELLED'],
                            ['status', '!=', 'DELIVERED'],
                        ])->first();
                        if ($newOrderWithShipping) {
                            $newOrderWithShipping->shipping_fee = $shippingCharge; //Assigining shipping charge to first of remaining order
                            $newOrderWithShipping->total = $newOrderWithShipping->subtotal + $shippingCharge;
                            $newOrderWithShipping->save();
                        } else {
                            DB::rollBack();
                            // Shipping charge will be appiled or shipping charge has not been received
                            return "Outstanding shipping charge of " . $shippingCharge . " detected.";
                        }
                    } elseif ($shippingCharge > 0 && !$deliveredWithoutShipping) {
                        $newOrderWithShipping = $myOrders->query()->where([
                            ['ref_number', $this->order->ref_number],
                            ['id', '!=', $this->order->id],
                            ['status', '!=', 'CANCELLED'],
                            ['status', '!=', 'DELIVERED'],
                        ])->first();
                        if ($newOrderWithShipping) {
                            $newOrderWithShipping->shipping_fee = $shippingCharge; //Assigining shipping charge to first of remaining order
                            $newOrderWithShipping->total = $newOrderWithShipping->subtotal + $shippingCharge;
                            $newOrderWithShipping->save();
                        } else {
                            DB::rollBack();
                            // Shipping charge will be appiled or shipping charge has not been received
                            return "Unable to allot shipping charge.";
                        }
                    }
                }
                $orderCompleted = $this->checkOrderStatus();
                if ($orderCompleted) {
                    // Process Cashback
                    $this->additionalDetail->update(['status' => 'COMPLETED', 'gogo_reward_redeem' => $redeemReward, 'order_cashback' => $cashback, 'shipping_charge' => $shippingCharge, 'order_total' => $newPaymentReceivable, 'total_refunded' => $this->additionalDetail->total_refunded + $refundableAmount, 'total_collected' => $this->additionalDetail->total_collected - $refundableAmount]);
                    if ($cashback > 0) {
                        $processCashback = $checkCashback->processCashback($this->order->user->id, $cashback);
                    }
                } else {
                    $this->additionalDetail->update(['gogo_reward_redeem' => $redeemReward, 'order_cashback' => $cashback, 'shipping_charge' => $shippingCharge, 'order_total' => $newPaymentReceivable, 'total_refunded' => $this->additionalDetail->total_refunded + $refundableAmount, 'total_collected' => $this->additionalDetail->total_collected - $refundableAmount]);
                }
            } else {
                $allCancelled = true;
                // Order Doesnot have other vendor orders or is all cancelled
                // Refund Redeem gogoPoint
                if ($this->additionalDetail->gogo_reward_redeem > 0) {
                    $refundGogoPoint = new RedeemReward($this->additionalDetail->order_total, $this->user);
                    $refundGogoPoint->refundGogoReward($this->additionalDetail->gogo_reward_redeem);
                }
                if ($this->order->payment_mode != 'cash on delivery') {
                    // Case: Single order and ePayment including gogoWallet
                    $refundableAmount = $this->additionalDetail->order_total  - $this->additionalDetail->donation;
                    $refund =  $this->ePaymentOrderRefund($this->order->payment_mode, $refundableAmount);
                    $this->additionalDetail->update(['status' => 'CANCELLED', 'gogo_reward_redeem' => 0, 'shipping_charge' => 0, 'order_cashback' => 0, 'order_total' => $this->additionalDetail->donation, 'total_refunded' => $this->additionalDetail->total_refunded + $refundableAmount, 'total_collected' => $this->additionalDetail->total_collected - $refundableAmount]);
                } else {
                    // Case: Single Order and Cash on Delivery  
                    $this->additionalDetail->update(['status' => 'CANCELLED', 'gogo_reward_redeem' => 0, 'shipping_charge' => 0, 'order_cashback' => 0, 'order_total' => 0, 'donation' => 0, 'total_refunded' => 0, 'total_collected' => 0]);
                }
            }
            // Cancel the order
            $this->order->shipping_fee = 0; //Shipping charge was applied in this product
            $this->order->total = $this->order->subtotal + $this->order->shipping_fee;
            $this->order->status = 'CANCELLED';
            $this->order->reason = $reason . "\r\n" . $this->order->reason;
            $this->order->save();
            // Finish Cancellation
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function ePaymentOrderRefund($mode, $refundAmount)
    {
        //Automated wallet refund
        if ($refundAmount > 0) {
            $refundGogoWallet = new gogoWallet($this->user, $refundAmount);
            if ($refundGogoWallet->refundGogoWallet()) {
                return true;
            }
            return false;
            // switch ($mode) {
            //     case 'gogoPoint':
            //         dd("I am here");
            //         $refundGogoWallet = new gogoWallet($this->user, $refundAmount);
            //         if ($refundGogoWallet->refundGogoWallet()) {
            //             return true;
            //         } //Automated wallet refund
            //         return false;
            //         break;
            //     default:
            //         $refundAmount = $refundAmount;

            //         return true;
            //         break;
            // }
        }
        return true;
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
