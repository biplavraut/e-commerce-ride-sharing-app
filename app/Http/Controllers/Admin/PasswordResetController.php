<?php

namespace App\Http\Controllers\Admin;

use App\Custom\Sms\Sparrow;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use App\Services\DriverService;
use App\Services\VendorService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\Admin\DriverResource;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Vendor\VendorResource;

class PasswordResetController extends CommonController
{
    /** @var UserService */
    private $userService;

    /** @var DriverService */
    private $driverService;

    /** @var VendorService */
    private $vendorService;

    /** @var AdminService */
    private $adminService;

    public function __construct(UserService $userService, DriverService $driverService, VendorService $vendorService, AdminService $adminService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->driverService = $driverService;
        $this->vendorService = $vendorService;
        $this->adminService = $adminService;
    }

    public function search(Request $request)
    {
        $data = null;
        if ($request->type == "user") {

            $data = UserResource::collection($this->userService->query()
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
                ->orWhere('email', $request->name)
                ->orWhere('phone', $request->name)->paginate($this->paginationLimit));
        }
        if ($request->type == "rider") {

            $data = DriverResource::collection($this->driverService->query()
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
                ->orWhere('email', $request->name)
                ->orWhere('phone', $request->name)->paginate($this->paginationLimit));
        }

        if ($request->type == "vendor") {

            $data = VendorResource::collection($this->vendorService->query()
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
                ->orWhere('business_name', $request->name)
                ->orWhere('email', $request->name)
                ->orWhere('phone', $request->name)->paginate($this->paginationLimit));
        }
        if ($request->type == "system") {

            $data = AdminResource::collection($this->adminService->query()
                ->where('type', '!=', 'superadmin')
                ->where(function ($query) use ($request) {
                    $query->where('name', $request->name);
                    $query->orWhere('email', $request->name);
                    $query->orWhere('phone', $request->name);
                })->paginate($this->paginationLimit));
        }

        return $data;
    }

    public function action(Request $request)
    {
        try {
            if ($request->type == "user") {
                $user = $this->userService->findOrFail($request->userId);
                $newpassword = Str::random(8);

                $user->update(['password' => bcrypt($newpassword)]);

                $this->sendSms($user->phone, $user->first_name, $newpassword);
                try {
                    if ($user->access_token) {
                        JWTAuth::setToken($user->access_token)->invalidate();
                    }
                } catch (\Throwable $th) {
                }
            }

            if ($request->type == "rider") {
                $driver = $this->driverService->findOrFail($request->userId);
                $newpassword = Str::random(8);

                $driver->update(['password' => bcrypt($newpassword)]);

                $this->sendSms($driver->phone, $driver->first_name, $newpassword);
            }

            if ($request->type == "vendor") {
                $vendor = $this->vendorService->findOrFail($request->userId);
                $newpassword = Str::random(8);

                $vendor->update(['password' => bcrypt($newpassword)]);

                $this->sendSms($vendor->phone, $vendor->business_name, $newpassword);
            }

            if ($request->type == "system") {
                $admin = $this->adminService->findOrFail($request->userId);
                $newpassword = "gogo20changeyourpassword";

                $admin->update(['password' => bcrypt($newpassword)]);

                if ($admin->phone) {
                    $this->sendSms($admin->phone, $admin->name, $newpassword);
                }
            }
        } catch (\Throwable $th) {
            return $th;
            return response("User Not Found.");
        }
        return response()->json(['message' => 'success', 'newPassword' => $newpassword]);
    }

    public function sendSms($phone, $name, $password)
    {
        $message = "Hello " . ucfirst($name) . ",\n";
        $message .= "Your new login password is: $password";
        $message .= "\nTeam gogo20";
        $sms = new Sparrow($phone, $message);
        $response = $sms->sendMessage();
    }
}
