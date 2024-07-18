<?php

namespace App\Http\Controllers\Api\Driver;

use Illuminate\Http\Request;
use App\Services\GogoAdService;
use App\Http\Resources\Api\AdResource;
use App\Http\Controllers\Api\CommonController;

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

    public function index()
    {
        return AdResource::collection($this->adService->query()->where('type', 'rider')->where('hide', 0)->get())->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
