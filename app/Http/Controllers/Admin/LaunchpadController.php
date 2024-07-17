<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LaunchpadRequest;
use App\Http\Resources\Admin\LaunchpadResource;
use App\LaunchpadCategory;
use App\Services\LaunchpadService;
use Illuminate\Http\Request;

class LaunchpadController extends CommonController
{
    /** @var LaunchpadService */
    private $launchpadService;

    public function __construct(LaunchpadService $launchpadService)
    {
        parent::__construct();
        $this->launchpadService = $launchpadService;
    }

    public function index()
    {
        $launchpads = $this->launchpadService->query()->orderBy('order')->paginate($this->paginationLimit);

        return LaunchpadResource::collection($launchpads);
    }

    public function store(LaunchpadRequest $request)
    {
        $launchpad = $this->launchpadService->store($request->validated());

        return new LaunchpadResource($launchpad);
    }

    public function show($launchpadId)
    {
        $launchpad = $this->launchpadService->findOrFail($launchpadId);

        return new LaunchpadResource($launchpad);
    }

    public function update(LaunchpadRequest $request, $launchpadId)
    {
        $launchpad = $this->launchpadService->update($launchpadId, $request->validated());

        return new LaunchpadResource($launchpad);
    }

    public function destroy($launchpadId)
    {
        $launchpad = $this->launchpadService->delete($launchpadId);

        return response('success');
    }

    public function byCategory(Request $request)
    {
        try {
            $category = LaunchpadCategory::findOrFail($request->id);
            return LaunchpadResource::collection($this->launchpadService->query()->where('launchpad_category_id', $category->id)->orderBy('order')->paginate($this->paginationLimit));
        } catch (\Throwable $th) {
            return response('error');
        }
    }

    public function search(Request $request)
    {
        return LaunchpadResource::collection($this->launchpadService->query()->where('name', 'LIKE', '%' . $request->name . '%')->take(10)->get());
    }

    public function changeOrder()
    {
        $this->launchpadService->changeOrder(request()->all());
    }
}
