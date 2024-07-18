<?php

namespace App\Http\Controllers\Api;

use App\AdditionalService;
use App\CheckPaymentLog;
use Illuminate\Http\Request;
use App\Custom\Paypoint\Soap;
use App\Helper\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Custom\Payment\gogo\gogoWallet;
use App\Http\Resources\Api\BillInfoResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Paypoint\PackageInfoResource;
use App\Http\Resources\Api\TransactionResource;
use App\Services\UtilityCouponCodeService;
use App\Services\UtilityVoucherService;
use App\User;
use App\UtilityCouponCodeHistory;
use App\UtilityVoucher;
use Exception;
use Google\Service\Spanner\TransactionSelector;
use Illuminate\Support\Facades\DB;

class PaypointController extends CommonController
{
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
        $this->utilityCouponCodeService    =   $utilityCouponCodeService;
        $this->utilityVoucherService    = $utilityVoucherService;
    }

    public function packageList(Request $request)
    {
        $providers = [
            "ncell-data" => [78, 3],
            "ncell-voice" => [78, 4],
            "ntc-data" => [585, 10],
            "ntc-voice" => [585, 11],
            "ntc-recommended" => [585, 12],
            "nea" => [598, 9999],
            "khanepani" => [761, 7],
        ];

        if ($request->service  == "internet") {
            $data = [
                [
                    "ID" => 1,
                    "Name" => 'Worldlink',
                    'Image' => ''
                ],
                [
                    "ID" => 2,
                    "Name" => 'Subisu',
                    'Image' => ''
                ],
                [
                    "ID" => 3,
                    "Name" => 'Vianet',
                    'Image' => ''
                ]
            ];
            return PackageInfoResource::collection(collect($data))->additional(['status' => true, 'statusCode' => 200, 'message' => ''], 200);
        }


        $paypoint = new Soap($providers[$request->service][0], $providers[$request->service][1]);

        if ($request->service == "nea" || $request->service == "khanepani") {
            $response = $paypoint->companyInfo();
        } else {
            $response = $paypoint->companyPackageInfo();
        }

        if ($response != null) {
            if ($request->service == "ntc-voice" || $request->service == "ntc-recommended") {
                $final = [$response];
                return PackageInfoResource::collection(collect($final))->additional(['status' => true, 'statusCode' => 200, 'message' => ''], 200);
            }

            return PackageInfoResource::collection(collect($response))->additional(['status' => true, 'statusCode' => 200, 'message' => ''], 200);
        }

        return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
    }

    public function topup(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|in:ncell,smart,ntc,ntc-postpaid,ntc-landline',
            'phone' => 'required|string|min:8|max:13',
            'amount' => 'required|numeric|min:10|max:5000',
            'couponCode' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        try {
            DB::beginTransaction();
            $transactionType = "no";
            $couponAmt = 0;
            if ($request->couponCode) {
                $isCodeValid = $this->utilityCouponCodeService->query()->where('code', $request->couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
                if (!$isCodeValid) {
                    $isCodeValid = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 0], ['user_id', $user->id]])->first();
                    if (!$isCodeValid) {
                        return failureResponse("Invalid Promo Code.", 404, 404);
                    } else {
                        $isAlreadyUsed = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 1], ['user_id', $user->id]])->first();
                        if ($isAlreadyUsed) {
                            return failureResponse("This Promo Code has already been used by you.", 200, 200);
                        }
                        $transactionType = "Voucher";
                        $couponAmt = $this->applyVoucherCode($isCodeValid, $user);
                    }
                } else {
                    $count = $isCodeValid->histories()->count();
                    if ($count < $isCodeValid->users) {
                        $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->where('coupon_code_id', $isCodeValid->id)->first();
                    } else {
                        return failureResponse("This Coupon Code has reached it's limit.", 200, 200);
                    }
                    // $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
                    if ($isAlreadyUsed) {
                        return failureResponse("This Promo Code has already been used by you.", 200, 200);
                    }
                    $transactionType = "Promo";
                    $couponAmt = $this->applyCode($isCodeValid, $user);
                }

                if ($request->amount < $couponAmt) {
                    return failureResponse("This Promo Amount exceeds requested amount.", 200, 200);
                }
            }
            if ($transactionType != "no") {
                $trans = $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $couponAmt, 'from' => $transactionType]);
            }
            $myBalance = 0;

            try {
                $myBalance = $user->gogoWallet->amount;
            } catch (\Throwable $th) {
                $myBalance = 0;
            }

            if ($myBalance + $couponAmt < $request->amount) {
                return failureResponse("You don't have enough gogoPoint to process this operation.", 422, 422);
            }

            $providers = [
                "ncell" => [78, 0],
                "smart" => [709, 0],
                "ntc" => [585, 0],
                "ntc-postpaid" => [585, 1],
                "ntc-landline" => [585, 2],
            ];

            $paypoint = new Soap($providers[$request->service][0], $providers[$request->service][1], $request->phone);

            $checkPaymentResponse = $paypoint->checkPayment();

            if ($checkPaymentResponse == false) {
                $message = $this->payPointErrorResponse();
                return failureResponse($message, 418, 418);
            }

            if ($checkPaymentResponse) {
                $response = $paypoint->executePayment($request->amount);

                if ($response) {
                    if ($request->amount - $couponAmt > 0) {
                        $wallet = new gogoWallet(auth()->guard('api')->user(), $request->amount - $couponAmt);
                        if ($wallet->operation()) {
                            $trans =  $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => $request->amount - $couponAmt, 'from' => 'Topup', 'type' => 'paid']);

                            $topupService = AdditionalService::where('cashback', '>', 0)->where('slug', 'topup')->first();
                            if ($topupService) {
                                $cashbackAmt = (($request->amount - $couponAmt) * $topupService->cashback) / 100;
                                if ($user->gogoWallet) {
                                    $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $cashbackAmt]);
                                } else {
                                    $user->gogoWallet()->create(['amount' => $cashbackAmt]);
                                }
                                $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $cashbackAmt, 'from' => 'Topup Cashback']);
                            }
                        }
                    }
                    // return failureResponse(ResponseMessage::PAYPOINT_ERROR, 200, 200);
                } else {
                    return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
                }
            } else {
                return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
            }


            DB::commit();
            return response()->json([
                'data' => new TransactionResource($trans),
                'status' => true,
                'message' => ResponseMessage::PAYPOINT_SUCCESS,
                'cashback' => $cashbackAmt,
                'statusCode' => 200
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return failureResponse("Something Went Wrong while processing your operation");
        }
    }

    public function packagePurchase(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }


        $validator = Validator::make($request->all(), [
            'service' => 'required|string|in:ncell-data,ncell-voice,ntc-data,ntc-voice,ntc-recommended',
            'packageRef' => 'required|string',
            'packageCost' => 'required',
            'phone' => 'required|string|min:10|max:10',
            'couponCode' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        try {
            DB::beginTransaction();
            $transactionType = "no";
            $couponAmt = 0;
            if ($request->couponCode) {
                $isCodeValid = $this->utilityCouponCodeService->query()->where('code', $request->couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
                if (!$isCodeValid) {
                    $isCodeValid = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 0], ['user_id', $user->id]])->first();
                    if (!$isCodeValid) {
                        return failureResponse("Invalid Promo Code.", 404, 404);
                    } else {
                        $isAlreadyUsed = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 1], ['user_id', $user->id]])->first();
                        if ($isAlreadyUsed) {
                            return failureResponse("This Promo Code has already been used by you.", 200, 200);
                        }
                        $transactionType = "Voucher";
                        $couponAmt = $this->applyVoucherCode($isCodeValid, $user);
                    }
                } else {
                    $count = $isCodeValid->histories()->count();
                    if ($count < $isCodeValid->users) {
                        $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->where('coupon_code_id', $isCodeValid->id)->first();
                    } else {
                        return failureResponse("This Coupon Code has reached it's limit.", 200, 200);
                    }
                    // $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
                    if ($isAlreadyUsed) {
                        return failureResponse("This Promo Code has already been used by you.", 200, 200);
                    }
                    $transactionType = "Promo";
                    $couponAmt = $this->applyCode($isCodeValid, $user);
                }

                if ($request->amount < $couponAmt) {
                    return failureResponse("This Promo Amount exceeds requested amount.", 200, 200);
                }
            }
            if ($transactionType != "no") {
                $trans = $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $couponAmt, 'from' => $transactionType]);
            }

            $myBalance = 0;

            try {
                $myBalance = $user->gogoWallet->amount;
            } catch (\Throwable $th) {
                $myBalance = 0;
            }

            if ($myBalance + $couponAmt < ($request->packageCost / 100)) {
                return failureResponse("You don't have enough gogoPoint to process this operation.", 422, 422);
            }

            $providers = [
                "ncell-data" => [78, 3],
                "ncell-voice" => [78, 4],
                "ntc-data" => [585, 10],
                "ntc-voice" => [585, 11],
                "ntc-recommended" => [585, 12]
            ];



            $paypoint = new Soap($providers[$request->service][0], $providers[$request->service][1], $request->phone);

            $checkPaymentResponse = $paypoint->checkPayment($request->packageRef);

            if ($checkPaymentResponse == false) {
                $message = $this->payPointErrorResponse();
                return failureResponse($message, 418, 418);
            }

            if ($checkPaymentResponse) {
                $response = $paypoint->executePayment(($request->packageCost / 100), $request->packageRef);

                if ($response) {
                    if (($request->packageCost / 100) - $couponAmt > 0) {
                        $wallet = new gogoWallet(auth()->guard('api')->user(), ($request->packageCost / 100) - $couponAmt);
                        if ($wallet->operation()) {
                            $trans = $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => ($request->packageCost / 100), 'from' => 'Package Purchase', 'type' => 'paid']);
                            $topupService = AdditionalService::where('cashback', '>', 0)->where('slug', 'topup')->first();
                            if ($topupService) {
                                $cashbackAmt = ((($request->packageCost / 100) - $couponAmt) * $topupService->cashback) / 100;
                                if ($user->gogoWallet) {
                                    $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $cashbackAmt]);
                                } else {
                                    $user->gogoWallet()->create(['amount' => $cashbackAmt]);
                                }
                                $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $cashbackAmt, 'from' => 'Package Purchase Cashback']);
                            }
                        }
                        // return failureResponse(ResponseMessage::PAYPOINT_ERROR, 200, 200);
                    }
                } else {
                    return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
                }
                // return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
            } else {
                return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
            }
            DB::commit();
            return response()->json([
                'data' => new TransactionResource($trans),
                'status' => true,
                'message' => ResponseMessage::PAYPOINT_SUCCESS,
                'cashback' => $cashbackAmt,
                'statusCode' => 200
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return failureResponse("Something Went Wrong while processing your operation");
        }
    }

    public function electricityCheck(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'index' => 'required',
            'scNo' => 'required',
            'customerId' => 'required',
            'customerMobile' => 'nullable' // only for checking pending deus, mandatory for payment
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $paypoint = new Soap(598, $request->index, $request->scNo);

        // $checkPaymentResponse = $paypoint->checkPayment($request->index, $request->scNo);
        $checkPaymentResponse = $paypoint->checkPayment($request->index, $request->customerId . '|' . $user->phone);

        if ($checkPaymentResponse == false) {
            $message = $this->payPointErrorResponse();
            return failureResponse($message, 418, 418);
        }

        if ($checkPaymentResponse != false) {
            return (new BillInfoResource($checkPaymentResponse['BillInfo']))->additional(['status' => true, 'message' => ResponseMessage::PAYPOINT_SUCCESS, 'statusCode' => 200], 200);
        } else {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
        }
    }

    public function electricity(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'index' => 'required',
            'scNo' => 'required',
            'amount' => 'required',
            'couponCode' => 'nullable|string',
            'customerId' => 'required',
            'customerMobile' => 'required'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }
        $transactionType = "no";
        $couponAmt = 0;
        if ($request->couponCode) {
            $isCodeValid = $this->utilityCouponCodeService->query()->where('code', $request->couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
            if (!$isCodeValid) {
                $isCodeValid = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 0], ['user_id', $user->id]])->first();
                if (!$isCodeValid) {
                    return failureResponse("Invalid Promo Code.", 404, 404);
                } else {
                    $isAlreadyUsed = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 1], ['user_id', $user->id]])->first();
                    if ($isAlreadyUsed) {
                        return failureResponse("This Promo Code has already been used by you.", 200, 200);
                    }
                    $transactionType = "Voucher";
                    $couponAmt = $this->applyVoucherCode($isCodeValid, $user);
                }
            } else {
                $count = $isCodeValid->histories()->count();
                if ($count < $isCodeValid->users) {
                    $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->where('coupon_code_id', $isCodeValid->id)->first();
                } else {
                    return failureResponse("This Coupon Code has reached it's limit.", 200, 200);
                }
                // $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
                if ($isAlreadyUsed) {
                    return failureResponse("This Promo Code has already been used by you.", 200, 200);
                }
                $transactionType = "Promo";
                $couponAmt = $this->applyCode($isCodeValid, $user);
            }

            if ($request->amount < $couponAmt) {
                return failureResponse("This Promo Amount exceeds requested amount.", 200, 200);
            }
        }

        $myBalance = 0;

        try {
            $myBalance = $user->gogoWallet->amount;
        } catch (\Throwable $th) {
            $myBalance = 0;
        }

        if ($myBalance + $couponAmt < ($request->amount / 100)) {
            return failureResponse("You don't have enough gogoPoint to process this operation.", 422, 422);
        }

        $paypoint = new Soap(598, $request->index, $request->scNo);

        // $response = $paypoint->executePayment(($request->amount / 100), null, $request->scNo);
        $response = $paypoint->executePayment(($request->amount / 100), null, $request->customerId . '|' . $request->customerMobile);

        if ($response) {
            $wallet = new gogoWallet(auth()->guard('api')->user(), ($request->amount / 100) - $couponAmt);
            if ($wallet->operation()) {
                $trans = $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => ($request->amount / 100), 'from' => 'Electricity', 'type' => 'paid']);

                $topupService = AdditionalService::where('cashback', '>', 0)->where('slug', 'bijuli')->first();
                if ($topupService) {
                    $cashbackAmt = ((($request->amount / 100) - $couponAmt) * $topupService->cashback) / 100;
                    if ($user->gogoWallet) {
                        $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $cashbackAmt]);
                    } else {
                        $user->gogoWallet()->create(['amount' => $cashbackAmt]);
                    }
                    $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $cashbackAmt, 'from' => 'Electricity Cashback']);
                    if ($transactionType != "no") {
                        $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $couponAmt, 'from' => $transactionType]);
                    }
                }

                return response()->json([
                    'data' => new TransactionResource($trans),
                    'status' => true,
                    'message' => ResponseMessage::PAYPOINT_SUCCESS,
                    'cashback' => $cashbackAmt,
                    'statusCode' => 200
                ], 200);
            }
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 200, 200);
        } else {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 200, 200);
        }
    }

    public function ispCheck(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:worldlink,vianet,subisu',
            'customerId' => 'required',
            'customerPhone' => 'required_if:provider,subisu'
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $providers = [
            "worldlink" => [597, 0],
            "subisu" => [596, 0],
            "vianet" => [716, 0]
        ];

        $paypoint = new Soap($providers[$request->provider][0], $providers[$request->provider][1], $request->customerId);

        $checkPaymentResponse = $paypoint->checkPayment($request->customerPhone ?? '');


        if ($checkPaymentResponse == false) {
            $message = $this->payPointErrorResponse();
            return failureResponse($message, 418, 418);
        }

        if ($checkPaymentResponse != false || $checkPaymentResponse != true) {
            return (new BillInfoResource($checkPaymentResponse['BillInfo']))->additional(['status' => true, 'message' => ResponseMessage::PAYPOINT_SUCCESS, 'statusCode' => 200], 200);
        } elseif ($checkPaymentResponse == true) {
            return successResponse(ResponseMessage::PAYPOINT_SUCCESS, 200, 200);
        } elseif ($checkPaymentResponse == false) {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
        } else {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
        }
    }

    public function ispPayment(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'provider' => 'required|in:worldlink,vianet,subisu',
            'customerId' => 'required',
            'customerPhone' => 'required_if:provider,subisu',
            'amount' => 'required',
            'couponCode' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $providers = [
            "worldlink" => [597, 0],
            "subisu" => [596, 0],
            "vianet" => [716, 0]
        ];

        $transactionType = "no";
        $couponAmt = 0;
        if ($request->couponCode) {
            $isCodeValid = $this->utilityCouponCodeService->query()->where('code', $request->couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
            if (!$isCodeValid) {
                $isCodeValid = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 0], ['user_id', $user->id]])->first();
                if (!$isCodeValid) {
                    return failureResponse("Invalid Promo Code.", 404, 404);
                } else {
                    $isAlreadyUsed = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 1], ['user_id', $user->id]])->first();
                    if ($isAlreadyUsed) {
                        return failureResponse("This Promo Code has already been used by you.", 200, 200);
                    }
                    $transactionType = "Voucher";
                    $couponAmt = $this->applyVoucherCode($isCodeValid, $user);
                }
            } else {
                $count = $isCodeValid->histories()->count();
                if ($count < $isCodeValid->users) {
                    $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->where('coupon_code_id', $isCodeValid->id)->first();
                } else {
                    return failureResponse("This Coupon Code has reached it's limit.", 200, 200);
                }
                // $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
                if ($isAlreadyUsed) {
                    return failureResponse("This Promo Code has already been used by you.", 200, 200);
                }
                $transactionType = "Promo";
                $couponAmt = $this->applyCode($isCodeValid, $user);
            }

            if ($request->amount < $couponAmt) {
                return failureResponse("This Promo Amount exceeds requested amount.", 200, 200);
            }
        }

        $myBalance = 0;

        try {
            $myBalance = $user->gogoWallet->amount;
        } catch (\Throwable $th) {
            $myBalance = 0;
        }

        if ($myBalance + $couponAmt < ($request->amount / 100)) {
            return failureResponse("You don't have enough gogoPoint to process this operation.", 422, 422);
        }

        $paypoint = new Soap($providers[$request->provider][0], $providers[$request->provider][1], $request->customerId);

        $response = $paypoint->executePayment(($request->amount / 100), $request->provider == "worldlink" ? $request->package : $request->customerPhone);

        if ($response) {
            $wallet = new gogoWallet(auth()->guard('api')->user(), ($request->amount / 100) - $couponAmt);
            if ($wallet->operation()) {
                $trans = $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => ($request->amount / 100), 'from' => 'Internet', 'type' => 'paid']);

                $topupService = AdditionalService::where('cashback', '>', 0)->where('slug', 'internet')->first();
                if ($topupService) {
                    $cashbackAmt = ((($request->amount / 100) - $couponAmt) * $topupService->cashback) / 100;
                    if ($user->gogoWallet) {
                        $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $cashbackAmt]);
                    } else {
                        $user->gogoWallet()->create(['amount' => $cashbackAmt]);
                    }
                    $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $cashbackAmt, 'from' => 'Internet Cashback']);
                    if ($transactionType != "no") {
                        $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $couponAmt, 'from' => $transactionType]);
                    }
                }

                return response()->json([
                    'data' => new TransactionResource($trans),
                    'status' => true,
                    'message' => ResponseMessage::PAYPOINT_SUCCESS,
                    'cashback' => $cashbackAmt,
                    'statusCode' => 200
                ], 200);
            }
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
        } else {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
        }
    }

    public function khanepaniCheck(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'index' => 'required',
            'customerId' => 'required',
            'month' => 'nullable',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }


        $paypoint = new Soap(761, $request->index, $request->customerId);

        $checkPaymentResponse = $paypoint->checkPayment($request->month ?? 1);

        if ($checkPaymentResponse == false) {
            $message = $this->payPointErrorResponse();
            return failureResponse($message, 418, 418);
        }

        if ($checkPaymentResponse != false) {
            return (new BillInfoResource($checkPaymentResponse['BillInfo']))->additional(['status' => true, 'message' => ResponseMessage::PAYPOINT_SUCCESS, 'statusCode' => 200], 200);
        } else {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 418, 418);
        }
    }

    public function khanepaniPayment(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

        $validator = Validator::make($request->all(), [
            'index' => 'required',
            'customerId' => 'required',
            'amount' => 'required',
            'couponCode' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        $transactionType = "no";
        $couponAmt = 0;
        if ($request->couponCode) {
            $isCodeValid = $this->utilityCouponCodeService->query()->where('code', $request->couponCode)->WhereDate('valid_till', '>=', date('Y-m-d'))->first();
            if (!$isCodeValid) {
                $isCodeValid = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 0], ['user_id', $user->id]])->first();
                if (!$isCodeValid) {
                    return failureResponse("Invalid Promo Code.", 404, 404);
                } else {
                    $isAlreadyUsed = $this->utilityVoucherService->query()->where([['code', $request->couponCode], ['used', 1], ['user_id', $user->id]])->first();
                    if ($isAlreadyUsed) {
                        return failureResponse("This Promo Code has already been used by you.", 200, 200);
                    }
                    $transactionType = "Voucher";
                    $couponAmt = $this->applyVoucherCode($isCodeValid, $user);
                }
            } else {
                $count = $isCodeValid->histories()->count();
                if ($count < $isCodeValid->users) {
                    $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->where('coupon_code_id', $isCodeValid->id)->first();
                } else {
                    return failureResponse("This Coupon Code has reached it's limit.", 200, 200);
                }
                // $isAlreadyUsed = UtilityCouponCodeHistory::where('user_id', $user->id)->Where('coupon_code_id', $isCodeValid->id)->first();
                if ($isAlreadyUsed) {
                    return failureResponse("This Promo Code has already been used by you.", 200, 200);
                }
                $transactionType = "Promo";
                $couponAmt = $this->applyCode($isCodeValid, $user);
            }

            if ($request->amount < $couponAmt) {
                return failureResponse("This Promo Amount exceeds requested amount.", 200, 200);
            }
        }
        $myBalance = 0;

        try {
            $myBalance = $user->gogoWallet->amount;
        } catch (\Throwable $th) {
            $myBalance = 0;
        }

        if ($myBalance + $couponAmt < ($request->amount / 100)) {
            return failureResponse("You don't have enough gogoPoint to process this operation.", 422, 422);
        }

        $paypoint = new Soap(761, $request->index, $request->customerId);

        $response = $paypoint->executePayment(($request->amount / 100));

        if ($response) {
            $wallet = new gogoWallet(auth()->guard('api')->user(), ($request->amount / 100) - $couponAmt);
            if ($wallet->operation()) {
                $trans = $user->transactionHistories()->create(['payment_mode' => 'gogoPoint', 'point' => ($request->amount / 100), 'from' => 'Khanepani', 'type' => 'paid']);

                $topupService = AdditionalService::where('cashback', '>', 0)->where('slug', 'khanepani')->first();
                if ($topupService) {
                    $cashbackAmt = ((($request->amount / 100) - $couponAmt) * $topupService->cashback) / 100;
                    if ($user->gogoWallet) {
                        $user->gogoWallet()->update(['amount' => $user->gogoWallet->amount + $cashbackAmt]);
                    } else {
                        $user->gogoWallet()->create(['amount' => $cashbackAmt]);
                    }
                    $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $cashbackAmt, 'from' => 'Khanepani Cashback']);
                    if ($transactionType != "no") {
                        $user->transactionHistories()->create(['payment_mode' => 'gogo20', 'point' => $couponAmt, 'from' => $transactionType]);
                    }
                }

                return response()->json([
                    'data' => new TransactionResource($trans),
                    'status' => true,
                    'message' => ResponseMessage::PAYPOINT_SUCCESS,
                    'cashback' => $cashbackAmt,
                    'statusCode' => 200
                ], 200);
            }
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 200, 200);
        } else {
            return failureResponse(ResponseMessage::PAYPOINT_ERROR, 200, 200);
        }
    }

    public function applyCode($code, $user)
    {
        $codeHistory = UtilityCouponCodeHistory::create(['user_id' => $user->id, 'coupon_code_id' => $code->id]);
        return $code->amount;
    }

    public function applyVoucherCode($code, $user)
    {
        $codeHistory = UtilityVoucher::where('id', $code->id)->update(['used' => 1]);
        return $code->amount;
    }

    public function payPointErrorResponse()
    {
        $errorStatement = CheckPaymentLog::where('user_id', auth()->guard('api')->id())->where('status', 0)->latest()->first();
        if ($errorStatement) {
            $response = json_decode($errorStatement->response, true);
            return $response['ResultMessage'];
        }
        return ResponseMessage::PAYPOINT_ERROR;
    }
}
