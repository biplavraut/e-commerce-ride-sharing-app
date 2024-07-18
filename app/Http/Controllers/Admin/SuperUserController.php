<?php

namespace App\Http\Controllers\Admin;

use App\Custom\Helper\EmailValidator;
use App\Custom\HttpRequest;
use App\Mail\SuperUserEmail;
use Illuminate\Http\Request;
use App\Services\AdminService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Requests\Admin\SuperUserRequest;

class SuperUserController extends CommonController
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

    public function index()
    {
        $superUsers = $this->adminService->getForIndex(
            $this->paginationLimit
        );

        return AdminResource::collection($superUsers);
    }

    public function store(SuperUserRequest $request)
    {
        $response = new EmailValidator($request->email);

        if (!$response->validate()) {
            return response()->json(['message' => 'The given data was invalid', 'errors' => ['email' => ['Email is invalid.']]], 422);
        }
        $user = $this->adminService->store($request->validated());
        $user->update(['password' => bcrypt('gogo20changeyourpassword'), 'verified' => 1]);

        try {
            Mail::to($user->email)->send(new SuperUserEmail($user, "gogo20changeyourpassword"));
        } catch (\Throwable $th) {
            throw $th;
        }

        return new AdminResource($user);
    }

    public function show($userId)
    {
        $user = $this->adminService->findOrFail($userId);

        return new AdminResource($user);
    }

    public function update(SuperUserRequest $request, $userId)
    {
        $user = $this->adminService->update($userId, $request->except('_method'));

        return new AdminResource($user);
    }

    public function updatePassword(Request $request, $userId)
    {
        $this->validate($request, [
            'password' => 'required|string|min:8',
            'confirmpassword' => 'required|string|min:8'
        ]);
        if ($request->password == $request->confirmpassword) {
            $user = $this->adminService->findOrFail($userId);
            $user->update(['password' => bcrypt($request->password)]);
            return new AdminResource($user);
        } else {
            return response()->json(['message' => 'The given data was invalid', 'errors' => ['password' => ['Password does not match.'], 'confirmpassword' => ['Confirm Password does not match.']]], 422);
        }
    }

    public function destroy($userId)
    {
        $user = $this->adminService->delete($userId);

        return response('success');
    }

    public function search(Request $request)
    {
        return AdminResource::collection($this->adminService->query()->where('type', '!=', 'superadmin')->where(function ($query) use ($request) {
            $query->where('name', 'LIKE', $request->name . '%');
            $query->orWhere('email', 'LIKE', $request->name . '%');
            $query->orWhere('phone', 'LIKE', $request->name . '%');
        })->paginate($this->paginationLimit));
    }

    public function markAsUnverified(Request $request)
    {
        $admin = $this->adminService->findOrFail($request->adminId);
        $message = "Success";
        if ($admin->isVerified()) {
            $admin->update(['verified' => 0]);
            $message = "Successfully marked as Unverified/Blocked.";
        } else {
            $admin->update(['verified' => 1]);
            $message = "Successfully marked as Verified/Active.";
        }

        return response($message);
    }
}
