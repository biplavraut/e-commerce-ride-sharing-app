<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\GogoAdService;
use App\Http\Requests\Admin\GogoAdRequest;
use App\Http\Resources\Admin\GogoAdResource;

class GogoAdController extends CommonController
{
    /** @var GogoAdService */
    private $gogoAdService;

    public function __construct(GogoAdService $gogoAdService)
    {
        parent::__construct();
        $this->gogoAdService = $gogoAdService;
    }

    public function index()
    {
        $ads = $this->gogoAdService->getPaginatedList($this->paginationLimit);

        return GogoAdResource::collection($ads);
    }

    public function store(GogoAdRequest $request)
    {
        $news = $this->gogoAdService->store();

        return new GogoAdResource($news);
    }

    public function show($adId)
    {
        $ad = $this->gogoAdService->findOrFail($adId, ['id', 'title', 'url', 'image', 'hide']);
        return new GogoAdResource($ad);
    }

    public function update(GogoAdRequest $request, $adId)
    {
        $ad = $this->gogoAdService->update($adId);

        return new GogoAdResource($ad);
    }

    public function destroy($adId)
    {
        $ad = $this->gogoAdService->delete($adId);
        $ad->deleteImage();

        return response('success');
    }

    public function search(Request $request)
    {
        $ads = $this->gogoAdService->query()->where('title', 'LIKE', $request->name . '%')->take(10)->get();
        return GogoAdResource::collection($ads);
    }
}
