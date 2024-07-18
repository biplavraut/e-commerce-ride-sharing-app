<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettlementController extends Controller
{

    public function settlement()
    {
        $vendor = auth()->guard('vendor-api')->user();

        if (!$vendor) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $advanceAmt = $vendor->advanceLogs()->sum('amount');

        return response()->json(
            [
                "data" => [
                    "advanceReceived" => round($advanceAmt),
                    'receivedFromTakeaway' => round($vendor->orders()->where('takeaway', 1)->where('payment_mode', 'cash on delivery')->where('status', 'DELIVERED')->sum('total') / 100),
                    'receivableFromGogo' => round($vendor->settleLogs()->sum('amount')),
                    'receivedFromGogo' => round($vendor->orders()->where('settle_status', 0)->where('status', 'DELIVERED')->sum('paying_total') / 100)
                ],
                "status" => true,
                'message' => '',
                'statusCode' => 200
            ],
            200
        );
    }
}
