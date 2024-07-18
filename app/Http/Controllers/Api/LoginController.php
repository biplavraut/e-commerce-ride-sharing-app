<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\Admin\UserResource;
use App\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /**
     * Log user in
     *
     * @param LoginRequest $request
     *
     * @return UserResource
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        if ($request->email) {
            $token = auth()->guard('api')->attempt([
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'verified' => 1
            ]);
        } else {
            $token = auth()->guard('api')->attempt([
                'country_code' => '+' . $request->input('countryCode'),
                'phone' => $request->input('phone'),
                'password' => $request->input('password'),
                'phone_verified' => 1
            ]);
        }

        if ($token) {
            if ($request->deviceToken && $request->deviceType) {
                $this->saveDevice(auth()->guard('api')->user(), $request);
            }
            if (auth()->guard('api')->user()->blocked == 1) {
                if (auth()->guard('api')->user()->access_token) {
                    JWTAuth::setToken(auth()->guard('api')->user()->access_token)->invalidate();
                }

                return failureResponse("Your account has been blocked. Please contact to support.", 401, 401);
            }
            //Generating refer code if not generated already
            $this->generateReferCode(auth()->guard('api')->user());

            //Last Login Time
            auth()->guard('api')->user()->update(['last_login_at' => now(), 'access_token' => $token]);

            return (new UserResource(auth()->guard('api')->user(), $token))->additional([
                'status' => true,
                'message' => "Login successful.", 'statusCode' => 200
            ], 200);
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
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        auth()->guard('api')->logout();

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
                'X-Access-Token'     => auth()->guard('api')->refresh(),
                'X-Token-Type'       => 'bearer',
                'X-Token-Expires-In' => auth()->guard('api')->factory()->getTTL() * 60,
            ]);
    }

    /**
     * Add token to blacklist
     *
     * @return \Exception
     */
    public function blacklistAccessToken()
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }
        auth()->guard('api')->invalidate();

        return successResponse('Token added to blacklist');
    }

    public function verifyEmail(Request $request)
    {
        $user = auth()->guard('api')->user();

        if (!$user) {
            return failureResponse("Token Expired.", 401, 401);
        }

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

    private function saveDevice($user, $request)
    {
        $user->devices()->create(['device_type' => $request->deviceType, 'device_token' => $request->deviceToken]);
    }

    public function generateReferCode($user)
    {
        if (!$user->refer_code) {

            $refer = "gogo" . $user->id;
            $code = strtoupper($refer . randomAlphaNumericString(5));

            $user->update(['refer_code' => substr($code, 0, 10)]);
        }
    }
}
