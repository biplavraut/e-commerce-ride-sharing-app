<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\GogoAdService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AdResource;

class AdController extends CommonController
{
    /**
     * @var GogoAdService
     */
    private $adService;


    public function __construct(GogoAdService $adService)
    {
        $this->adService = $adService;
    }

    public function index(Request $request)
    {
        return AdResource::collection($this->adService->query()->where('type', 'user')
        ->where('hide', 0)
        ->whereIn('service_id', [0, $request->serviceId])->get())
        ->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
