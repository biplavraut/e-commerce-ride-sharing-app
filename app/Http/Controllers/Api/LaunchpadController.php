<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CommonController;
use App\Http\Resources\Api\Launchpad\LaunchpadCategoryResource;
use App\Services\LaunchpadCategoryService;
use App\Services\LaunchpadService;

class LaunchpadController extends CommonController
{
    /**
     * @var LaunchpadCategoryService
     */
    private $launchpadCategoryService;

    /**
     * @var LaunchpadService
     */
    private $launchpadService;

    public function __construct(LaunchpadCategoryService $launchpadCategoryService, LaunchpadService $launchpadService)
    {
        $this->launchpadCategoryService = $launchpadCategoryService;
        $this->launchpadService = $launchpadService;
    }

    public function index()
    {
        return LaunchpadCategoryResource::collection($this->launchpadCategoryService->query()->orderBy('order')->get())->additional(['status' => true, 'message' => '', 'statusCode' => 200], 200);
    }
}
