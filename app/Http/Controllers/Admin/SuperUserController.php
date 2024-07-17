<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AdminService;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Admin\AdminResource;
use App\Mail\SuperUserEmail;

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

    public function store(Request $request)
    {
        $user = $this->adminService->store($request->all());

        try {
            Mail::to($user->email)->send(new SuperUserEmail($user, "gogo20changeyourpassword"));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new AdminResource($user);
    }

    public function show($userId)
    {
        $user = $this->adminService->findOrFail($userId);

        return new AdminResource($user);
    }

    public function update(Request $request, $userId)
    {
        $user = $this->adminService->update($userId, $request->except('_method'));

        return new AdminResource($user);
    }

    public function destroy($userId)
    {
        $user = $this->adminService->delete($userId);

        return response('success');
    }

    public function search(Request $request)
    {
        return AdminResource::collection($this->adminService->query()->Where('type', '!=', 'superadmin')->where('name', 'LIKE', '%' . $request->name . '%')->Where('email', 'LIKE', '%' . $request->name . '%')->take(10)->get());
    }
}
