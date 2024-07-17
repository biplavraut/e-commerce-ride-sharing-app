<?php

namespace App\Http\Controllers\Api;

use App\Order;
use App\UserCart;
use App\OrderItem;
use App\PaymentLog;
use App\CouponCodeHistory;
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

    public function __construct(OrderService $orderService, OrderItemService $orderItemService, CouponCodeService $couponCodeService)
    {
        $this->orderService         =   $orderService;
        $this->orderItemService    =   $orderItemService;
        $this->couponCodeService    =   $couponCodeService;
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
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $orders  =  $this->orderService->insert($request);
        if ($orders == ResponseMessage::ERROR) {
            return failureResponse(ResponseMessage::ERROR, 500, 500);
        }

        $orderItemResponse  =   $this->orderItemService->insert($orders, $request);
        //empty user cart
        UserCart::where('user_id', $user->id)->delete();
        if ($orderItemResponse == ResponseMessage::ERROR) :
            foreach ($orders as $order) {
                $order->delete();
            }
            return failureResponse(ResponseMessage::ERROR, 500, 500);
        endif;

        $couponAmt = 0;

        $totalPayment = $this->orderService->updateTotal($orders);


        if ($request->couponCode) {
            $isCodeValid = $this->couponCodeService->query()->where('code', $request->couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
            if (!$isCodeValid) {
                return failureResponse("Invalid Coupon Code.", 404, 404);
            }
            $isAlreadyUsed = CouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
            if ($isAlreadyUsed) {
                return failureResponse("This Coupon Code has already been used by you.", 200, 200);
            }

            $couponAmt = $this->applyCode($isCodeValid, $user);
        }


        if ($request->donationTrust && $request->donation) {
            $this->donationProcess($request->donationTrust, $request->donation, $user);
            $totalPayment += $request->donation;
        }


        try {
            DB::beginTransaction();
            //check for payment Verification
            $log = $this->paymentVerification($request->paymentType, ($totalPayment - $couponAmt), $request->token);
            if ($log != false) {
                foreach ($orders as $mOrder) {
                    $this->paymentLog($mOrder->total, $request, true, "ondemand service purchase", $mOrder->id, $log);
                }
                DB::commit();
                return successResponse(ResponseMessage::OrderSuccess, 201, 201);
            } else {
                foreach ($orders as $order) {
                    $order->delete();
                }
                $this->paymentLog(($totalPayment - $couponAmt), $request, false, "ondemand service purchase", null, null);
                DB::commit();
                return failureResponse(ResponseMessage::PAYMENTFAILED, 500, 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
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

    public function applyCode($code, $user)
    {
        $codeHistory = CouponCodeHistory::create(['user_id' => $user->id, 'coupon_code_id' => $code->id]);
        return $code->amount;
    }

    public function donationProcess($trust, $amount, $user)
    {
        $user->donations()->create(['trust' => $trust, 'donation' => $amount]);
    }

    public function cancleOrder(Request $request)
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $getOrder = $user->orders()->where("id", $request->order_id)->first();
        if (!empty($getOrder) && $getOrder->status == "PENDING") {
            ///cancel the order
            $getOrder->status = "CANCELLED";
            $getOrder->save();

            $notification = new PushNotification(
                [$user->device->device_token],
                [
                    'title' => 'Cancelled By User',
                    'message' => 'Your Order ' . $getOrder->ref_number . '  has been cancelled by you.',
                    'type' => 'order-cancelled',
                ]
            );
            $notification->send();

            return successResponse("Your order is successfully cancelled", 200, 200);
        } else {
            ///Order is already assigned
            return failureResponse("Unable to cancel this order", 418, 418);
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
            'user_id' => auth()->guard('api')->id(),
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
}
