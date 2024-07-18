<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\UserCart;
use App\OrderItem;
use App\PaymentLog;
use App\CouponCodeHistory;
use App\Custom\Order\CashBack;
use App\Custom\Order\CouponCode;
use App\Custom\Order\ProcessCancellation;
use App\Custom\Order\RedeemReward;
use App\Custom\Order\ShippingCharge;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Helper\ResponseMessage;
use App\Custom\PushNotification;
use App\Services\OrderItemService;
use Illuminate\Support\Facades\DB;
use App\Services\CouponCodeService;
use App\Http\Controllers\Controller;
use App\Custom\Payment\gogo\gogoWallet;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Resources\Api\OrderResource;
use App\Custom\Payment\Esewa\EsewaResponse;
use App\Custom\Payment\Khalti\KhaltiResponse;
use App\Custom\Sms\AakashSms;
use App\Custom\Sms\Sparrow;
use App\DefaultConf;
use App\Http\Resources\Api\OrderFeedbackResource;
use App\Notifications\OrderFeedbackReceived;
use App\OrderAdditionalDetail;
use App\OrderFeedback;
use App\Services\VoucherService;
use App\Voucher;
use Exception;
use Firebase\FirebaseLib;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var OrderItem
     */
    private $orderItemService;

    /**
     * @var CouponCodeService
     */
    private $couponCodeService;

    /**
     * @var VoucherService
     */
    private $voucherService;

    protected $firebase;


    public function __construct(OrderService $orderService, OrderItemService $orderItemService, CouponCodeService $couponCodeService, VoucherService $voucherService)
    {
        $this->orderService         =   $orderService;
        $this->orderItemService    =   $orderItemService;
        $this->couponCodeService    =   $couponCodeService;
        $this->voucherService = $voucherService;

        $this->firebase = new FirebaseLib(env('FIREBASE_DATABASEURL'), env('FIRBASE_ADMIN_SEC'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        return OrderResource::collection($user->orders()->orderBy('created_at', 'desc')->paginate(10))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\OrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        // User Authentication
        $user = auth()->guard('api')->user();
        if (!$user || $user->isBlocked() || $request->donation > 20) {
            $user->update(['blocked' => 1]);
            auth()->guard('api')->logout();
            if ($user->access_token) {
                JWTAuth::setToken($user->access_token)->invalidate();
            }
            return failureResponse("Token Expired.", 401, 401);
        }
        if ($user->cart->count() == 0) {
            return failureResponse("Empty Cart", 422, 422);
        }
        // Order Request added Validation
        if ($request->paymentType == "esewa" || $request->paymentType == "khalti") {
            $oldTrans = PaymentLog::where('token', $request->token)->where('payment_mode', $request->paymentType)->first();
            if ($oldTrans) {
                $user->update(['blocked' => 1]);
                auth()->guard('api')->logout();
                if ($user->access_token) {
                    JWTAuth::setToken($user->access_token)->invalidate();
                }
                return failureResponse('Transaction already existed. Suspiceous Transaction. Account Blocked.', 403, 403);
            }
        }

        // Shipping Distance
        if ($request->latitude && $request->longitude) {
            //getting air distance of status and pickup point
            $distance =  number_format((float) getDistance(27.733623151865437, 85.34126043319702, $request->latitude, $request->longitude), 2, '.', '');
            // dd($distance);
            if ($distance > 50) {
                return failureResponse('Shipping service Not available in this region.', 403, 403);
            }
        }
        // Start of placing order
        try {
            DB::beginTransaction();
            $orders  =  $this->orderService->insert($request); //Create an order for each vendors
            if ($orders == ResponseMessage::ERROR) {
                DB::rollBack();
                return failureResponse(ResponseMessage::ERROR, 500, 500);
            }
            $orderRef = $orders[0]->ref_number; // Order Refrence for Order Additional Detail
            $orderItemResponse  =   $this->orderItemService->insert($orders, $request); //Create order items detail for each orders
            if ($orderItemResponse == ResponseMessage::OUTOFSTOCK || $orderItemResponse == ResponseMessage::ERROR) :
                // foreach ($orders as $order) {
                //     $order->delete();
                // }
                DB::rollBack();
                return failureResponse($orderItemResponse, 500, 500);
            endif;

            $totalPayment = $this->orderService->updateTotal($orders);

            // Calculating Shipping
            if ($orders[0]->takeaway == 1) {
                $shippingCharge = 0;
            } else {
                $shipping = new ShippingCharge($totalPayment, $orders[0]->delivery_location);
                $shippingCharge = $shipping->calculateShipping();
            }

            $this->orderService->updatePayingTotal($orders, $shippingCharge);
            // Checking Coupon or Voucher Applicable
            $couponAmt = 0;
            $couponCode = "";
            if ($request->couponCode) {
                $coupon = new CouponCode($this->couponCodeService, $this->voucherService);
                $couponAmt = $coupon->couponValidation($request->couponCode);
            }
            if ($couponAmt > 0) {
                $couponCode = $request->couponCode;
                $user->transactionHistories()->create(['payment_mode' => 'gogoPromo', 'point' => $couponAmt, 'type' => 'paid', 'from' => 'Order: Promo used']);
            }
            // Redeem Reward
            $redeemReward = 0;
            if ($request->isRedeem == true || $request->isRedeem == 1) {
                $reward = new RedeemReward($totalPayment + $shippingCharge - $couponAmt, $user);
                $redeemReward = $reward->calculateRedeem();
                if (!$redeemReward) {
                    // foreach ($orders as $order) {
                    //     $order->delete();
                    // }
                    DB::rollBack();
                    return failureResponse("Not enough gogoPoint to Redeem", 422, 422);
                } else {
                    $user->transactionHistories()->create(['payment_mode' => 'gogoReward', 'point' => $redeemReward, 'type' => 'paid', 'from' => 'Order: Reward point redeem']);
                }
            }
            // Donation
            $donation = 0;
            if ($request->donationTrust && $request->donation && $request->donation <= 20) {
                $this->donationProcess($request->donationTrust, $request->donation, $user);
                $donation = $request->donation;
            }

            // Payment Verification
            $cashback = 0;
            $lastOrder = null;
            $defaultConf = DefaultConf::firstOrFail();
            if ($totalPayment < $defaultConf->min_order_limit) {
                // foreach ($orders as $order) {
                //     $order->delete();
                // }
                DB::rollBack();
                return failureResponse("Minimum Order limit is Rs. " . $defaultConf->min_order_limit, 422, 422);
            }
            try {
                $paymentReceivable = $totalPayment + $shippingCharge - $redeemReward + $donation - $couponAmt;
                $log = $this->paymentVerification($request->paymentType, $paymentReceivable, $request->token);
                if ($log != false) {
                    foreach ($orders as $mOrder) {
                        $this->paymentLog($mOrder->total, $request, true, "Ondemand service purchase", $mOrder->id, $log);
                        $lastOrder = $mOrder->payment_mode;
                    }

                    $pendingOrders = $this->orderService->query()->where('status', 'PENDING')->count();

                    $this->firebase->set(env('ORDER_PATH', 'orders/') . $mOrder->id, $orders);
                    $this->firebase->set(env('PENDING_PATH', 'pending/'), $pendingOrders);
                    try {
                        $checkCashback = new CashBack($totalPayment + $shippingCharge);
                        $cashback = $checkCashback->calculateCashback();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return failureResponse("Could not process cashback.", 422, 422);
                    }

                    try {
                        // User log
                        if ($cashback > 0) {
                            $user->transactionHistories()->create(['payment_mode' => 'gogoReward', 'point' => $cashback, 'from' => 'order cashback: pending']);
                        }
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        return failureResponse("Transaction could not be created.", 422, 422);
                    }


                    $totalCollected = 0;
                    if ($request->paymentType != "cod") {
                        $totalCollected = $paymentReceivable;   // ePayment total collected is amount paid by user
                        $user->transactionHistories()->create(['payment_mode' => $request->paymentType, 'point' => $paymentReceivable, 'from' => 'Order Ref: ' . $orders[0]->ref_number, 'type' => 'paid']);
                    }
                    $userIdx = (int)$user->id;
                    try {
                        $additionalDetail = OrderAdditionalDetail::create([
                            'order_ref_number' => $orderRef ?? '',
                            'user_id' => $userIdx ?? 0,
                            'coupon_code' => $couponCode ?? '',
                            'coupon_discount' => $couponAmt ?? '',
                            'shipping_charge' => $shippingCharge ?? '',
                            'gogo_reward_redeem' => $redeemReward ?? '',
                            'order_total' => $paymentReceivable ?? '',
                            'donation' => $donation ?? '',
                            'order_cashback' => $cashback ?? '',
                            'total_collected' => $totalCollected ?? 0
                        ]);
                        //empty user cart

                    } catch (\Throwable $th) {
                        // foreach ($orders as $order) {
                        //     $order->delete();
                        // }
                        DB::rollBack();
                        return $th;
                    }
                    UserCart::where('user_id', $user->id)->delete();
                } else {
                    // foreach ($orders as $order) {
                    //     $order->delete();
                    // }
                    DB::rollBack();
                    $this->paymentLog($paymentReceivable, $request, false, "Ondemand service purchase", null, null);
                    return failureResponse(ResponseMessage::PAYMENTFAILED, 422, 422);
                }
            } catch (\Throwable $th) {
                // foreach ($orders as $order) {
                //     $order->delete();
                // }
                DB::rollBack();
                return failureResponse("Something Went Wrong while processing your payment", 422, 422);
            }
            DB::commit();
            $vendorPhone = [];
            $total = [];
            foreach ($orders as $order) {
                if ($order->vendor->phone != "") {
                    array_push($vendorPhone, $order->vendor->phone);
                    array_push($total, $order->subtotal);
                }
            }
            $this->sendVendorSMS($vendorPhone, $total);
            if ($order->alternate_phone != '') {
                $this->sendUserSMS($order);
            }
            return successResponse(ResponseMessage::OrderSuccess, 200, 200);
        } catch (Exception $e) {
            // foreach ($orders as $order) {
            //     $order->delete();
            // }
            DB::rollBack();
            return failureResponse("Something Went Wrong while processing your operation", 422, 422);
            // return ResponseMessage::ERROR;
        }
    }

    public function paymentVerification($mode, $total, $token = null)
    {
        switch ($mode) {
            case 'cod':
                return true;
                break;

            case 'gogoWallet':
                $wallet = new gogoWallet(auth()->guard('api')->user(), $total);
                if ($wallet->operation()) {
                    return $wallet;
                }
                return false;
                break;

            case 'esewa':
                $esewa = new EsewaResponse($token);
                if ($esewa->status() == 'approved') {
                    return $esewa;
                }
                return false;
                break;

            case 'khalti':
                $khalti = new KhaltiResponse($token, $total * 100);
                if ($khalti->status() == 'approved') {
                    return $khalti->transId();
                }
                return false;
                break;

            default:
                return false;
                break;
        }
    }

    public function donationProcess($trust, $amount, $user)
    {
        $user->donations()->create(['trust' => $trust, 'donation' => $amount]);
    }

    public function cancelOrder(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $getOrder = $user->orders()->where("id", $request->order_id)->first();
        if (!empty($getOrder) && $getOrder->status == "PENDING") {
            ///cancel the order
            $processCancellation = new ProcessCancellation($getOrder, $user);
            $updateOrder = $processCancellation->checkCancellation('Cancelled by user.');
            if ($updateOrder === true) {
                $notification = new PushNotification(
                    $user->devices->pluck('device_token')->toArray(),
                    [
                        'title' => 'Cancelled By User',
                        'message' => 'Order No. ' . $getOrder->ref_number . ' has been cancelled by you.',
                        'type' => 'order-cancelled',
                    ]
                );
                $notification->send();
                $user->myNotifications()->create(['title' => 'Order cancelled', 'message' => 'Order No. ' . $getOrder->ref_number . '  has been cancelled by you.', 'type' => 'order']);
                return successResponse("Your order is successfully cancelled.", 200, 200);
            } else {
                if ($updateOrder === false) {
                    return failureResponse("Unable to process order cancellation.", 418, 418);
                } else {
                    return failureResponse($updateOrder, 418, 418);
                }
            }
            // $getOrder->status = "CANCELLED";
            // $getOrder->save();

            // if ($getOrder->payment_mode != "cash on delivery") {
            //     if ($getOrder->payment_mode == "gogoPoint") {
            //         $user->update(['reward_point' => $user->reward_point + round($getOrder->total)]);
            //         // $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $getOrder->total]);
            //         $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => $getOrder->total, 'from' => 'Order Refund']);
            //     } else {
            //         $getOrder->update(['refundable_amount' => $getOrder->total]);
            //     }
            // }

            // $notification = new PushNotification(
            //     $user->devices->pluck('device_token')->toArray(),
            //     [
            //         'title' => 'Cancelled By User',
            //         'message' => 'Order No. ' . $getOrder->ref_number . ' has been cancelled by you.',
            //         'type' => 'order-cancelled',
            //     ]
            // );
            // $notification->send();

            // $user->myNotifications()->create(['title' => 'Order cancelled', 'message' => 'Order No. ' . $getOrder->ref_number . '  has been cancelled by you.', 'type' => 'order']);


            // return successResponse("Your order is successfully cancelled.", 200, 200);
        } else {
            ///Order is already assigned
            return failureResponse("Unable to cancel this order.", 418, 418);
        }
    }

    public function paymentLog($bill,  $request, $success, $action, $orderId, $tranId)
    {

        $transId = "";
        if ($request->paymentType == "esewa") {
            $transId = $request->token;
        } else if ($request->paymentType == "khalti") {
            $transId = $tranId;
        }

        $log = PaymentLog::create([
            'user_id' => auth()->guard('api')->user()->id,
            'task_id' => $orderId,
            'token' => $transId,
            'bill_amt' => $bill,
            'verified' => $success,
            'payment_mode' => $request->paymentType,
            'ip' => request()->getClientIp(),
            'agent' => request()->header('User-Agent'),
            'action' => $action,
            'type' => 'order'
        ]);
    }

    public function orderFeedback(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        try {
            $order = $this->orderService->query()->where('user_id', $user->id)->where('id', $request->order)->first();
            $feedback = OrderFeedback::where('order_id', $order->id)->where('user_id', $user->id)->first();

            if ($order && !$feedback) {
                $myFeedback = OrderFeedback::create(['user_id' => $user->id, 'order_id' => $order->id, 'feedback' => $request->feedback]);
                $data['first_name'] = $user->first_name;
                $data['last_name'] = $user->last_name;
                $data['order_id'] = $order->id;
                $data['image'] = $user->imageUrl();
                $data['message'] = 'Order feedback received.';
                Notification::send($user, new OrderFeedbackReceived($data));

                return successResponse('Feedback sent successfully.');

                return (new OrderFeedbackResource($myFeedback))->additional(['status' => true, 'message' => "Feedback sent successfully.", 'statusCode' => 200], 200);
            } else if ($order && $feedback) {
                return successResponse('You already sent feedback for this order.');
                return (new OrderFeedbackResource($feedback))->additional(['status' => true, 'message' => "You already sent feedback for this order.", 'statusCode' => 200], 200);
            } else {
                return failureResponse("Order Not Found.", 404, 404);
            }
        } catch (\Throwable $th) {
            return failureResponse("Order Not Found.", 404, 404);
        }
    }

    public function sendVendorSMS($numbers, $total)
    {
        $numberFormat = null;

        foreach ($numbers as $key => $number) {
            $numberFormat .= $number . ",";
        }

        $message = 'Dear Vendor' . ", \n";
        $message .= 'gogo20 Order has been received.' . " \n";
        $message .= 'Please click here: https://gogo20.com/vendor/login' . " \n";
        $message .= 'Thank You!';


        try {
            $sms = new Sparrow($numberFormat, $message);
            $response = $sms->sendMessage();

            if ($response != 200) {
                $sms = new AakashSms(null, $numberFormat, $message);
                $response = $sms->sendMessage();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return true;
    }

    public function sendUserSMS($order)
    {
        $numberFormat = null;
        $numberFormat .= $order->alternate_phone . ",";

        $message = 'Namaste ' . $order->alternate_name .  " Ji, \n";
        $message .= 'Your order has been placed and confirmed WRT your details worth of Rs.' . $order->total . '/- (COD).' . " \n";
        $message .= 'Thank You - Team gogo20';
        try {
            $sms = new Sparrow($numberFormat, $message);
            $response = $sms->sendMessage();

            if ($response != 200) {
                $sms = new AakashSms(null, $numberFormat, $message);
                $response = $sms->sendMessage();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return true;
    }
}
