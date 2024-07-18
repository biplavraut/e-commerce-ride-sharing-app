<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\CouponCodeService;
use App\Http\Requests\Admin\CouponCodeRequest;
use App\Http\Resources\Admin\CouponCodeResource;

class CouponCodeController extends CommonController
{
	/** @var CouponCodeService */
	private $couponCodeService;

	public function __construct(CouponCodeService $couponCodeService)
	{
		parent::__construct();
		$this->couponCodeService = $couponCodeService;
	}

	public function index()
	{
		$couponCodes = $this->couponCodeService->getForIndex(
			$this->paginationLimit
		);

		return CouponCodeResource::collection($couponCodes);
	}

	public function store(CouponCodeRequest $request)
	{
		$couponCode = $this->couponCodeService->store($request->validated());

		return new CouponCodeResource($couponCode);
	}

	public function show($couponCodeId)
	{
		$couponCode = $this->couponCodeService->findOrFail($couponCodeId);

		return new CouponCodeResource($couponCode);
	}

	public function update(CouponCodeRequest $request, $couponCodeId)
	{
		$couponCode = $this->couponCodeService->update($couponCodeId, $request->validated());

		return new CouponCodeResource($couponCode);
	}

	public function destroy($couponCodeId)
	{
		$couponCode = $this->couponCodeService->delete($couponCodeId);

		return response('success');
	}

	public function search(Request $request)
	{
		return CouponCodeResource::collection($this->couponCodeService->query()->where('code', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
	}
}
