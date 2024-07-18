<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductAttributeRequest;
use App\Http\Resources\Admin\ProductAttributeResource;
use App\Services\ProductAttributeService;

class ProductAttributeController extends CommonController
{
	/** @var ProductAttributeService */
	private $productAttributeService;

	public function __construct(ProductAttributeService $productAttributeService)
	{
		parent::__construct();
		$this->productAttributeService = $productAttributeService;
	}

	public function index()
	{
		$productAttributes = $this->productAttributeService->getForIndex(
			$this->paginationLimit
		);

		return ProductAttributeResource::collection($productAttributes);
	}

	public function store(ProductAttributeRequest $request)
	{
		$productAttribute = $this->productAttributeService->store($request->validated());

		return new ProductAttributeResource($productAttribute);
	}

	public function show($productAttributeId)
	{
		$productAttribute = $this->productAttributeService->findOrFail($productAttributeId);

		return new ProductAttributeResource($productAttribute);
	}

	public function update(ProductAttributeRequest $request, $productAttributeId)
	{
		$productAttribute = $this->productAttributeService->update($productAttributeId);

		return new ProductAttributeResource($productAttribute);
	}

	public function destroy($productAttributeId)
	{
		$productAttribute = $this->productAttributeService->delete($productAttributeId);

		return response('success');
	}
}
