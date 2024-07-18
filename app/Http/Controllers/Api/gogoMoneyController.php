<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\WalletPaymentLog;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Custom\Payment\Esewa\EsewaResponse;
use App\Custom\Payment\Khalti\KhaltiResponse;
use App\Http\Resources\Api\TransactionResource;

class gogoMoneyController extends CommonController
{
    /** @var UserService */
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    public function load(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'paymentType' => 'required|in:khalti,esewa,imepay|string',
            'token' => 'required_if:paymentType,esewa,imepay,khalti|string',
            'amount' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $oldTrans = WalletPaymentLog::where('token', $request->token)->where('payment_mode', $request->paymentType)->first();

        if ($oldTrans) {
            return failureResponse('Transaction already existed. You are trying to fool us. Now you are in a trap.', 403, 403);
        }

        try {
            $data = $this->paymentVerification($request->paymentType, $request->amount, $request->token);

            if ($data[1]) {

                $this->log($request, $data[0], true);

                if ($request->amount == $data[0]->totalAmount()) {
                    if ($user->gogoWallet) {
                        $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $request->amount]);
                    } else {
                        $user->gogoWallet()->create(['amount' => $request->amount]);
                    }
                } else {
                    return failureResponse('You are trying to fool us. Now you are in a trap.', 403, 403);
                }

                $user->transactionHistories()->create(['payment_mode' => $request->paymentType, 'point' => $request->amount, 'from' => 'Load gogoPoint']);
                $user->myNotifications()->create(['title' => 'gogoPoint', 'message' => 'gogoPoint successfully loaded using ' . $request->paymentType . '.', 'type' => 'point']);
                return successResponse('gogoPoint has been successfully loaded.', 200, 200);
            } else {
                $this->log($request, $data[0], false);
                return failureResponse('Something went wrong while verifying transaction.', 418, 418);
            }
        } catch (\Throwable $th) {
            return failureResponse('Something went wrong while executing action.', 418, 418);
        }
    }

    public function paymentVerification($mode, $total, $token = null)
    {
        switch ($mode) {
            case 'esewa':
                $esewa = new EsewaResponse($token);
                if ($esewa->status() == 'approved') {
                    return [$esewa, true];
                }
                return [$esewa, false];
                break;

            case 'khalti':
                $khalti = new KhaltiResponse($token, $total * 100);
                if ($khalti->status() == 'approved') {
                    return [$khalti, true];
                }
                return [$khalti, false];
                break;

            default:
                return [null, false];
                break;
        }
    }

    public function log($request, $response, $status)
    {
        $transId = "";

        if ($request->paymentType == "khalti" && $status) {
            $transId = $response->transId();
        } else if ($request->paymentType == "khalti" && !$status) {
            $transId = $request->token;
        } else if ($request->paymentType == "esewa") {
            $transId = $request->token;
        }

        $log = WalletPaymentLog::create([
            'user_id' => auth()->guard('api')->id(),
            'ip' => request()->getClientIp(),
            'agent' => request()->header('User-Agent'),
            'action' => "Amount Loaded to gogoMoney",
            'token' => $transId,
            'bill_amt' => $request->amount,
            'verified' => $status,
            'payment_mode' => $request->paymentType,
            'response' => json_encode($response)
        ]);
    }

    public function history()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return TransactionResource::collection($user->transactionHistories()->latest()->paginate(10))->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }

    public function historyFilter(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $logs = $user->transactionHistories()->whereBetween('created_at', [$request->from, Carbon::parse($request->to)->addDay()])->orderBy('created_at', 'DESC')->paginate(10);
        return TransactionResource::collection($logs)->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
