<?php

namespace App\Http\Controllers\Api;

use App\UtilityCouponCodeHistory;
use App\Services\UtilityCouponCodeService;
use App\Http\Controllers\Controller;
use App\Services\UtilityVoucherService;
use Illuminate\Http\Request;

class UtilityCouponController extends Controller
{
    //
    /**
     * @var UtilityCouponCodeService
     */
    private $utilityCouponCodeService;
    /**
     * @var UtilityVoucherService
     */
    private $utilityVoucherService;

    public function __construct(UtilityCouponCodeService $utilityCouponCodeService, UtilityVoucherService $utilityVoucherService)
    {
        $this->utilityCouponCodeService         =   $utilityCouponCodeService;
        $this->utilityVoucherService    = $utilityVoucherService;
    }

    public function check(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $isCodeValid = $this->utilityCouponCodeService->query()->where('code', $request->code)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();

        if (!$isCodeValid) {
            $isCodeValid = $this->utilityVoucherService->query()->where([['code', $request->code],['used',0],['user_id', $user->id]])->first();
            if(!$isCodeValid){
                return failureResponse("Invalid Promo Code.", 404, 404);
            }else{
                $isAlreadyUsed = $this->utilityVoucherService->query()->where([['code', $request->code],['used',1],['user_id', $user->id]])->first();
            }
        }else{
            $count = $isCodeValid->histories()->count();
            if($count < $isCodeValid->users){
                $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->where('coupon_code_id', $isCodeValid->id)->first();
            }else{
                return failureResponse("This Coupon Code has reached it's limit.", 200, 200);
            }
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
