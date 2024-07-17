<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Notifications\SettingsUpdated;
use App\Services\AdminService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminController extends CommonController
{
    /**
     * @var AdminService
     */
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        parent::__construct();
        $this->adminService = $adminService;
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $admin = auth()->guard('admin')->user();

        $status = $this->adminService->changePassword($admin, $request);

        return response()->json($status);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string',
            'image'    => 'nullable|file|max:5120|mimes:jpg,jpeg,png',
            'email'    => 'required|email',
            'password' => 'nullable|string|confirmed',
        ]);

        $admin = auth()->guard('admin')->user();

        $admin  = $this->adminService->updateByModel($admin, $data);

        Notification::send($admin, new SettingsUpdated(User::first(), "Hey Setting has been updated."));

        return new AdminResource($admin);
    }
}
