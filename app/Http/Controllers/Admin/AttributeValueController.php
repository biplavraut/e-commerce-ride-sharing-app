<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AttributeValueRequest;
use App\Http\Resources\Admin\AttributeValueResource;
use App\Services\AttributeValueService;

class AttributeValueController extends CommonController
{
	/** @var AttributeValueService */
	private $attributeValueService;

	public function __construct(AttributeValueService $attributeValueService)
	{
		parent::__construct();
		$this->attributeValueService = $attributeValueService;
	}

	public function index()
	{
		$attributeValues = $this->attributeValueService->getForIndex(
			$this->paginationLimit
		);

		return AttributeValueResource::collection($attributeValues);
	}

	public function store(AttributeValueRequest $request)
	{
		$attributeValue = $this->attributeValueService->store($request->validated());

		return new AttributeValueResource($attributeValue);
	}

	public function show($attributeValueId)
	{
		$attributeValue = $this->attributeValueService->findOrFail($attributeValueId);

		return new AttributeValueResource($attributeValue);
	}

	public function update(AttributeValueRequest $request, $attributeValueId)
	{
		$attributeValue = $this->attributeValueService->update($attributeValueId);

		return new AttributeValueResource($attributeValue);
	}

	public function destroy($attributeValueId)
	{
		$attributeValue = $this->attributeValueService->delete($attributeValueId);

		return response('success');
	}
}
