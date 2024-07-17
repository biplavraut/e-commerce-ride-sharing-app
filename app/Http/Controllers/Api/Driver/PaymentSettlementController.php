<?php

namespace App\Http\Controllers\Api\Driver;

use App\DriverPaymentSettlement;
use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\PaymentSettlementResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
