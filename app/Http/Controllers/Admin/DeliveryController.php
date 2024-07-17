<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\DeliveryService;
use App\Http\Requests\Admin\DeliveryRequest;
use App\Http\Resources\Admin\DeliveryResource;
use App\Driver;
use App\Delivery;
use App\Order;
use App\Services\OrderService;
use Firebase\FirebaseLib;
use App\Custom\PushNotification;

class DeliveryController extends CommonController
{
	/** @var DeliveryService */
	private $deliveryService;

	public function __construct(DeliveryService $deliveryService)
	{
		parent::__construct();
		$this->deliveryService = $deliveryService;
	}

	public function index()
	{
		$deliveries = $this->deliveryService->getForIndex(
			$this->paginationLimit
		);

		return DeliveryResource::collection($deliveries);
	}

	public function store(DeliveryRequest $request)
	{
		$delivery = $this->deliveryService->store($request->validated());

		return new DeliveryResource($delivery);
	}

	public function show($deliveryId)
	{
		$delivery = $this->deliveryService->findOrFail($deliveryId);

		return new DeliveryResource($delivery);
	}

	public function update(DeliveryRequest $request, $deliveryId)
	{
		$delivery = $this->deliveryService->update($deliveryId, $request->validated());

		return new DeliveryResource($delivery);
	}

	public function destroy($deliveryId)
	{
		$delivery = $this->deliveryService->delete($deliveryId);

		return response('success');
	}

	public function search(Request $request)
	{
		return DeliveryResource::collection($this->deliveryService->query()->where('status', 'LIKE', '%' . $request->name . '%')->take(10)->get());
	}

}
