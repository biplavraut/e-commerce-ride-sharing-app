<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LaunchpadCategoryRequest;
use App\Http\Resources\Admin\LaunchpadCategoryResource;
use App\Services\LaunchpadCategoryService;
use Illuminate\Http\Request;

class LaunchpadCategoryController extends CommonController
{
    /** @var LaunchpadCategoryService */
    private $launchpadCategoryService;

    public function __construct(LaunchpadCategoryService $launchpadCategoryService)
    {
        parent::__construct();
        $this->launchpadCategoryService = $launchpadCategoryService;
    }

    public function index()
    {
        $launchpadCategories = $this->launchpadCategoryService->getForIndex(
            $this->paginationLimit
        );

        return LaunchpadCategoryResource::collection($launchpadCategories);
    }

    public function store(LaunchpadCategoryRequest $request)
    {
        $launchpadCategory = $this->launchpadCategoryService->store($request->validated());

        return new LaunchpadCategoryResource($launchpadCategory);
    }

    public function show($launchpadCategoryId)
    {
        $launchpadCategory = $this->launchpadCategoryService->findOrFail($launchpadCategoryId);

        return new LaunchpadCategoryResource($launchpadCategory);
    }

    public function update(LaunchpadCategoryRequest $request, $launchpadCategoryId)
    {
        $launchpadCategory = $this->launchpadCategoryService->update($launchpadCategoryId, $request->validated());

        return new LaunchpadCategoryResource($launchpadCategory);
    }

    public function destroy($launchpadCategoryId)
    {
        $launchpadCategory = $this->launchpadCategoryService->delete($launchpadCategoryId);

        return response('success');
    }

    public function changeOrder()
    {
        $this->launchpadCategoryService->changeOrder(request()->all());
    }

    public function getAll()
    {
        return LaunchpadCategoryResource::collection($this->launchpadCategoryService->getCategories());
    }

    public function search(Request $request)
    {
        return LaunchpadCategoryResource::collection($this->launchpadCategoryService->query()->where('name', 'LIKE', '%'.$request->name.'%')->take(10)->get());
    }
}
