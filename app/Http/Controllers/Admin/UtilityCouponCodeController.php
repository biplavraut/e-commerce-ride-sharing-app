<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UtilityCouponCodeService;
use App\Http\Requests\Admin\UtilityCouponCodeRequest;
use App\Http\Resources\Admin\UtilityCouponCodeResource;

class UtilityCouponCodeController extends CommonController
{
    /** @var UtilityCouponCodeService */
	private $utilityCouponCodeService;

	public function __construct(UtilityCouponCodeService $utilityCouponCodeService)
	{
		parent::__construct();
		$this->utilityCouponCodeService = $utilityCouponCodeService;
	}

	public function index()
	{
		$couponCodes = $this->utilityCouponCodeService->getForIndex(
			$this->paginationLimit
		);

		return UtilityCouponCodeResource::collection($couponCodes);
	}

	public function store(UtilityCouponCodeRequest $request)
	{
		$couponCode = $this->utilityCouponCodeService->store($request->validated());

		return new UtilityCouponCodeResource($couponCode);
	}

	public function show($couponCodeId)
	{
		$couponCode = $this->utilityCouponCodeService->findOrFail($couponCodeId);

		return new UtilityCouponCodeResource($couponCode);
	}

	public function update(UtilityCouponCodeRequest $request, $couponCodeId)
	{
		$couponCode = $this->utilityCouponCodeService->update($couponCodeId, $request->validated());

		return new UtilityCouponCodeResource($couponCode);
	}

	public function destroy($couponCodeId)
	{
		$couponCode = $this->utilityCouponCodeService->delete($couponCodeId);

		return response('success');
	}

	public function search(Request $request)
	{
		return UtilityCouponCodeResource::collection($this->utilityCouponCodeService->query()->where('code', 'LIKE', $request->name . '%')->paginate($this->paginationLimit));
	}
}
