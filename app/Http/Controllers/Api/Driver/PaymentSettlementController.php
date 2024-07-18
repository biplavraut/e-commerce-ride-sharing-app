<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\DriverPaymentSettlement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Custom\Payment\Esewa\EsewaResponse;
use App\Custom\Payment\Khalti\KhaltiResponse;
use App\DriverPaymentLog;
use App\Http\Resources\Driver\PaymentSettlementResource;

class PaymentSettlementController extends Controller
{

    public function index()
    {
        $user = auth()->guard('driver-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        return (new PaymentSettlementResource($user->settlement))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }


    public function store(Request $request)
    {
        $user = auth()->guard('driver-api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:bank,wallet',
            'bankName' => 'required_if:type,==,bank',
            'accountName' => 'required_if:type,==,bank',
            'accountNo' => 'required_if:type,==,bank',
            'branch' => 'required_if:type,==,bank',
            'walletProvider' => 'required_if:type,==,wallet',
            'walletNo' => 'required_if:type,==,wallet',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }


        if (!$user->settlement) {
            $settlement = DriverPaymentSettlement::create(
                [
                    'driver_id' => $user->id,
                    'type' => $request->type,
                    'bank_name' => $request->bankName,
                    'branch' => $request->branch,
                    'account_name' => $request->accountName,
                    'account_no' => $request->accountNo,
                    'wallet_provider' => $request->walletProvider,
                    'wallet_no' => $request->walletNo
                ]
            );

            return (new PaymentSettlementResource($settlement))->additional(['status' => true, 'message' => "Your Payment details has been saved.", 'statusCode' => 200], 201);
        } else {
            $settlement = $user->settlement->update([
                'type' => $request->type,
                'bank_name' => $request->bankName,
                'branch' => $request->branch,
                'account_name' => $request->accountName,
                'account_no' => $request->accountNo,
                'wallet_provider' => $request->walletProvider,
                'wallet_no' => $request->walletNo
            ]);
            return (new PaymentSettlementResource($user->settlement))->additional(['status' => true, 'message' => "Your Payment details has been updated.", 'statusCode' => 200], 204);
        }

        return failureResponse("Something went wrong.", 418, 418);
    }

    public function pay(Request $request)
    {
        $user = auth()->guard('driver-api')->user();

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

        try {

            $settlement = $user->settlement;

            if ($settlement) {
                if (round($settlement->payable_amount + $settlement->donation_amount) == $request->amount) {
                    $data = $this->paymentVerification($request->paymentType, $request->amount, $request->token);
                    if ($data[1]) {
                        $settlement->update(['payable_amount' => 0, 'donation_amount' => 0]);
                        $this->log($data, $user->id, $request);

                        return response()->json([
                            'data' => [
                                'verified' => $user->isVerified(),
                                'rating' => $user->averageRating(),
                                'isBlocked' => $user->is_blocked == 1,
                                'blockedReason' => $user->is_blocked == 1 ? $user->log : '',
                                'blackListed' => $user->blacklisted ?? 0,
                                'totalCompletedTrips' => $user->completedTrips->count(),
                                'completedTrips' => $user->completedTrips()->whereDate('completed_at', date('Y-m-d'))->count(),
                                'completedDeliveries' => $user->completedDeliveries()->whereDate('delivered_at', date('Y-m-d'))->count(),
                                'cancelledTrips' => $user->cancelledTrips->count(),
                                'payableAmount' => round($user->settlement ? $user->settlement->payable_amount : 0) + round($user->settlement ? $user->settlement->donation_amount : 0),
                                'donationAmount' => round($user->settlement ? $user->settlement->donation_amount : 0),
                                'receivableAmount' => round($user->settlement ? $user->settlement->receivable_amount : 0),
                                'lifeTimeEarning' => round($user->completedTrips->sum('price')),
                                'totalEarning' => round($user->completedTrips()->whereDate('completed_at', date('Y-m-d'))->sum('price')),
                                'rideSoFar' => $user->completedTrips->sum('distance1'),
                                'isDocumentSubmitted' => $user->documentState() == 0,
                                'interestedIn' => $user->interested_in ?? ""
                            ],
                            'status' => true,
                            'statusCode' => 200
                        ], 200);
                    } else {
                        $this->log($data, $user->id, $request);
                        return failureResponse('Payment verification process failed.', 418, 418);
                    }
                } else {
                    return failureResponse('Payable Amount doesnot match with paying amount.', 422, 422);
                }
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
                return false;
                break;
        }
    }

    public function log($response, $riderId, $request)
    {
        $transId = "";

        if ($request->paymentType == "khalti" && $response[1]) {
            $transId = $response[0]->transId();
        } else if ($request->paymentType == "khalti" && !$response[1]) {
            $transId = $request->token;
        } else if ($request->paymentType == "esewa") {
            $transId = $request->token;
        }

        $log = DriverPaymentLog::create([
            'driver_id' => $riderId,
            'ip' => request()->getClientIp(),
            'agent' => request()->header('User-Agent'),
            'action' => "Commission Amount Paid",
            'token' => $transId,
            'bill_amt' => $request->amount,
            'verified' => $response[1],
            'payment_mode' => $request->paymentType,
            'response' => json_encode($response[0])
        ]);
    }
}
