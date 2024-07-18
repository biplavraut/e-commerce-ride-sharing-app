<?php

namespace App\Http\Controllers\Api;

use App\CouponCodeHistory;
use Illuminate\Http\Request;
use App\Services\CouponCodeService;
use App\Http\Controllers\Controller;
use App\Services\VoucherService;

class CouponController extends Controller
{
    /**
     * @var CouponCodeService
     */
    private $couponCodeService;
    /**
     * @var VoucherService
     */
    private $voucherService;
    public function __construct(CouponCodeService $couponCodeService, VoucherService $voucherService)
    {
        $this->couponCodeService         =   $couponCodeService;
        $this->voucherService   = $voucherService;
    }

    public function check(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $isCodeValid = $this->couponCodeService->query()->where('code', $request->code)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();

        if (!$isCodeValid) {
            $isCodeValid = $this->voucherService->query()->where([['code', $request->code],['used',0],['user_id', $user->id]])->first();
            if(!$isCodeValid){
                return failureResponse("Invalid Promo Code.", 404, 404);
            }else{
                $isAlreadyUsed = $this->voucherService->query()->where([['code', $request->code],['used',1],['user_id', $user->id]])->first();
            }
            // return failureResponse("Invalid Coupon Code.", 404, 404);
        }else{
            $isAlreadyUsed = CouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
        }


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
