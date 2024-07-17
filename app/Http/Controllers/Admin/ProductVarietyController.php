<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductVarietyRequest;
use App\Http\Resources\Admin\ProductVarietyResource;
use App\Services\ProductVarietyService;

class ProductVarietyController extends CommonController
{
	/** @var ProductVarietyService */
	private $productVarietyService;

	public function __construct(ProductVarietyService $productVarietyService)
	{
		parent::__construct();
		$this->productVarietyService = $productVarietyService;
	}

	public function index()
	{
		$productVarieties = $this->productVarietyService->getForIndex(
			$this->paginationLimit
		);

		return ProductVarietyResource::collection($productVarieties);
	}

	public function store(ProductVarietyRequest $request)
	{
		$productVariety = $this->productVarietyService->store();

		return new ProductVarietyResource($productVariety);
	}

	public function show($productVarietyId)
	{
		$productVariety = $this->productVarietyService->findOrFail($productVarietyId);

		return new ProductVarietyResource($productVariety);
	}

	public function update(ProductVarietyRequest $request, $productVarietyId)
	{
		$productVariety = $this->productVarietyService->update($productVarietyId);

		return new ProductVarietyResource($productVariety);
	}

	public function destroy($productVarietyId)
	{
		$productVariety = $this->productVarietyService->delete($productVarietyId);

		return response('success');
	}
}
