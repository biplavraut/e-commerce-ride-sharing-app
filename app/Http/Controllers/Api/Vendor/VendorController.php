<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\CommonController;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\VendorRegisterRequest;
use App\Http\Resources\Vendor\VendorResource;
use App\Mail\VendorVerifyEmail;
use App\Otp;
use App\Services\VendorService;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VendorController extends CommonController
{

    /** @var VendorService */
    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        parent::__construct();
        $this->vendorService = $vendorService;
    }

    public function register(VendorRegisterRequest $request)
    {
        if (!$this->verifyMyOtp($request->otp, '+' . $request->countryCode . $request->phone)) {
            return failureResponse("otp does not match", 404, 404);
        }

        $vendor = Vendor::create([
            'business_name' => $request->businessName,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'country_code' => "+" . $request->countryCode,
            'phone' => $request->phone,
            'email_token' => str_random(10),
            'password' => bcrypt($request->password),
            'city' => $request->city,
            'address' => $request->address,
            'partnership_type' => $request->partnershipType,
            'lat' => $request->lat,
            'long' => $request->long,
        ]);
        $vendor = $this->vendorService->update($vendor->id, $request->only('image'));
        $vendor->verifyPhone();
        Otp::where('otp', $request->otp)->Where('phone', '+' . $request->countryCode . $request->phone)->delete();
        try {
            Mail::to($vendor)->send(new VendorVerifyEmail($vendor->email_token));
        } catch (\Throwable $th) {
            //throw $th;
        }
        if($request->from == "APP"){
            return successResponse('Vendor account successfully created');
        }
        return new VendorResource($vendor);
    }

    public function login(LoginRequest $request)
    {
        if ($request->email) {
            $token = auth()->guard('vendor-api')->attempt([
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'verified' => 1
            ]);
        } else {
            $token = auth()->guard('vendor-api')->attempt([
                'country_code' => $request->input('countryCode'),
                'phone' => $request->input('phone'),
                'password' => $request->input('password'),
                'verified' => 1
            ]);
        }

        if ($token) {
            $vendor = auth()->guard('vendor-api')->user();
            $vendor['token'] = $token;
            return (new VendorResource($vendor))->additional(['status' => true, 'message' => "Login successful.", 'statusCode' => 200], 200);
        }

        //check verify
        if ($request->email) {
            $tokensecond = auth()->guard('vendor-api')->attempt([
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
            ]);
        } else {
            $tokensecond = auth()->guard('vendor-api')->attempt([
                'country_code' => $request->input('countryCode'),
                'phone' => $request->input('phone'),
                'password' => $request->input('password'),
            ]);
        }

        if ($tokensecond) {
            return failureResponse("You will be able to login when you are verified by gogo20 Team", 401, 401);
        }

        return failureResponse("These credentials do not match our records.", 401, 401);

        throw new \Exception(__('auth.failed'), Response::HTTP_UNAUTHORIZED);
    }


    /**
     * Log user out
     *
     */
    public function logout()
    {
        auth()->guard('vendor-api')->logout();

        return successResponse('Successfully logged out.');
    }

    /**
     * Refresh login access token of user
     *
     * @return UserResource
     */
    public function refreshAccessToken()
    {
        return successResponse('Token refreshed successfully.')
            ->withHeaders([
                'X-Access-Token'     => auth()->guard('vendor-api')->refresh(),
                'X-Token-Type'       => 'bearer',
                'X-Token-Expires-In' => auth()->guard('vendor-api')->factory()->getTTL() * 60,
            ]);
    }

    /**
     * Add token to blacklist
     *
     * @return \Exception
     */
    public function blacklistAccessToken()
    {
        auth()->guard('vendor-api')->invalidate();

        return successResponse('Token added to blacklist');
    }

    public function verifyEmail(Request $request)
    {
        $user = auth()->guard('vendor-api')->user();

        $validator = Validator::make($request->all(), [
            'token' => 'required|string|min:10|max:10',
        ]);

        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $errors = $messages[0];
            }
            return failureResponse($errors, 418, 418);
        }

        try {
            $user->where('email_token', $request->token)->firstOrFail()->verifyEmail();
            return successResponse("E-mail is verified now.");
        } catch (\Throwable $th) {
            return failureResponse("Token doesn't match.", 404, 404);
        }
    }

    public function verifyMyOtp($otp, $phone)
    {
        try {
            $isExist = Otp::where('otp', $otp)->Where('phone', $phone)->firstOrFail();
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
