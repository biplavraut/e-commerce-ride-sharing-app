<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Services\VoucherService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VoucherResource;

class VoucherController extends CommonController
{
    /** @var VoucherService */
    private $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        parent::__construct();
        $this->voucherService = $voucherService;
    }

    public function index()
    {
        $vouchers = $this->voucherService->getForIndex(
            $this->paginationLimit
        );

        return VoucherResource::collection($vouchers);
    }

    public function store(Request $request)
    {
        $exist = $this->voucherService->query()->where('code', $request->code)->where('user_id', $request->user_id)->count();
        if ($exist > 0) {
            return "Code exist";
        }
        DB::transaction(function () use ($request, &$voucher) {
            $data = $request->except('_token');


            $voucher = $this->voucherService->store($data);


            return new VoucherResource($voucher);
        });
    }

    public function show($voucherId)
    {
        $voucher = $this->voucherService->findOrFail($voucherId);

        return new VoucherResource($voucher);
    }

    public function update(Request $request, $voucherId)
    {
        $data = $request->except('_method');
        $voucher = $this->voucherService->update($voucherId, $data);

        return new VoucherResource($voucher);
    }

    public function destroy($voucherId)
    {
        $voucher = $this->voucherService->delete($voucherId);

        return response('success');
    }

    public function search(Request $request)
    {
        return VoucherResource::collection($this->voucherService->query()->where('code', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
    }

    public function userSearch(Request $request)
    {
        if ($request->ajax() && strlen($request->name) > 0) {
            $users = User::select(['id', 'first_name', 'last_name', 'phone', 'email'])
                ->where("phone", 'LIKE', $request->name . "%")
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
                ->orWhere("email", 'LIKE', $request->name . "%")->take(10)->get();

            return $users;
        }

        return [];
    }
}
