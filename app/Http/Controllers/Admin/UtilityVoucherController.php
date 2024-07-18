<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UtilityVoucherResource;
use App\Services\UtilityVoucherService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilityVoucherController extends CommonController
{
    //
    /** @var UtilityVoucherService */
    private $utilityVoucherService;

    public function __construct(UtilityVoucherService $utilityVoucherService)
    {
        parent::__construct();
        $this->utilityVoucherService = $utilityVoucherService;
    }

    public function index()
    {
        $vouchers = $this->utilityVoucherService->getForIndex(
            $this->paginationLimit
        );

        return UtilityVoucherResource::collection($vouchers);
    }

    public function store(Request $request)
    {
        $exist = $this->utilityVoucherService->query()->where('code', $request->code)->where('user_id', $request->user_id)->count();
        if ($exist > 0) {
            return "Code exist";
        }
        DB::transaction(function () use ($request, &$voucher) {
            $data = $request->except('_token');


            $voucher = $this->utilityVoucherService->store($data);


            return new UtilityVoucherResource($voucher);
        });
    }

    public function show($voucherId)
    {
        $voucher = $this->utilityVoucherService->findOrFail($voucherId);

        return new UtilityVoucherResource($voucher);
    }

    public function update(Request $request, $voucherId)
    {
        $data = $request->except('_method');
        $voucher = $this->utilityVoucherService->update($voucherId, $data);

        return new UtilityVoucherResource($voucher);
    }

    public function destroy($voucherId)
    {
        $voucher = $this->utilityVoucherService->delete($voucherId);

        return response('success');
    }

    public function search(Request $request)
    {
        return UtilityVoucherResource::collection($this->utilityVoucherService->query()->where('code', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
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
