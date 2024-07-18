<?php

namespace App\Http\Controllers\Api;

use App\EliteUserRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;

class EliteController extends CommonController
{
    /** @var UserService */
    private $userService;

    private $user;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;

        $this->user = auth()->guard('api')->user();

        if (!$this->user) {
            return failureResponse("Token Expired.", 401, 401);
        }
    }

    public function request(Request $request)
    {

        if ($this->user->eliteRequest) {
            return successResponse("You already requested for gogoElite. Thank you.");
        }

        if ($this->user->elite == 1) {
            return successResponse("You already owned gogoElite Badge. Thank you.");
        }

        $eliteRequest = EliteUserRequest::create(['user_id' => $this->user->id]);

        return successResponse("Thank you for your interest to be a part of gogoElite. We will get back to you after a short review on your transactions and other behaviour at our services. In the mean time, keep using our services. Stay safe! \n- Team gogo20");
    }
}
