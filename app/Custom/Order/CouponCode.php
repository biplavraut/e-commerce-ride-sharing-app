<?php

namespace App\Custom\Order;

use App\CouponCodeHistory;
use App\DefaultConf;
use App\Services\CouponCodeService;
use App\Services\VoucherService;

// Coupon and Voucher Code validation for order
class CouponCode
{
    public function __construct($couponCodeService, $voucherService)
    {
        $this->couponCodeService    =   $couponCodeService;
        $this->voucherService = $voucherService;
        $this->user = auth()->guard('api')->user();
    }

    public function couponValidation($couponCode)
    {
        if ($couponCode) {
            $isCodeValid = $this->couponCodeService->query()->where('code', $couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
            if (!$isCodeValid) {
                $isCodeValid = $this->voucherService->query()->where([['code', $couponCode], ['used', 0], ['user_id', $this->user->id]])->first();
                if (!$isCodeValid) {
                    // return failureResponse("Invalid Promo Code.", 404, 404);
                    $couponAmt = 0;
                } else {
                    $isAlreadyUsed = $this->voucherService->query()->where([['code', $couponCode], ['used', 1], ['user_id', $this->user->id]])->first();
                    if ($isAlreadyUsed) {
                        // return failureResponse("This Voucher Code has already been used by you.", 200, 200);
                        $couponAmt = 0;
                    } else {
                        $couponAmt = $this->applyVoucherCode($isCodeValid);
                    }
                }
                // return failureResponse("Invalid Coupon Code.", 404, 404);
            } else {
                $isAlreadyUsed = CouponCodeHistory::where('user_id', $this->user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
                if ($isAlreadyUsed) {
                    // return failureResponse("This Coupon Code has already been used by you.", 200, 200);
                    $couponAmt = 0;
                } else {
                    $couponAmt = $this->applyCode($isCodeValid);
                }
            }
            return $couponAmt;
        }
    }

    public function applyCode($code)
    {
        $codeHistory = CouponCodeHistory::create(['user_id' => $this->user->id, 'coupon_code_id' => $code->id]);
        return $code->amount;
    }

    public function applyVoucherCode($code)
    {
        $codeHistory = $this->voucherService->query()->where('id', $code->id)->update(['used' => 1]);
        return $code->amount;
    }
}
