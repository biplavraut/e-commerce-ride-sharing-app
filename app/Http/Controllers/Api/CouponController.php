<?php

namespace App\Http\Controllers\Api;

use App\CouponCodeHistory;
use Illuminate\Http\Request;
use App\Services\CouponCodeService;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    /**
     * @var CouponCodeService
     */
    private $couponCodeService;

    public function __construct(CouponCodeService $couponCodeService)
    {
        $this->couponCodeService         =   $couponCodeService;
    }

    public function check(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $isCodeValid = $this->couponCodeService->query()->where('code', $request->code)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();

        if (!$isCodeValid) {
            return failureResponse("Invalid Coupon Code.", 404, 404);
        }

        $isAlreadyUsed = CouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();

        if ($isAlreadyUsed) {
            return failureResponse("This Coupon Code has already been used by you.", 200, 200);
        } else {
            // $create = CouponCodeHistory::create(['user_id' => $user->id, 'coupon_code_id' => $isCodeValid->id]);

            return response()->json([
                'amount' => $isCodeValid->amount,
                'status'     => true,
                'message'    => "Coupon code has been applied",
                'statusCode' => 200,
            ], 200);
        }
    }
}
