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

    /**
     * @var LaunchpadService
     */
    private $launchpadService;

    public function __construct(GogoAdService $adService)
    {
        $this->adService = $adService;
    }

    public function index()
    {
        return AdResource::collection($this->adService->query()->where('hide', 0)->get())->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
