<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AcademyContentService;
use App\Http\Requests\Admin\AcademyContentRequest;
use App\Http\Resources\Admin\AcademyContentResource;

class AcademyContentController extends CommonController
{
    /** @var AcademyContentService */
    private $contentService;

    public function __construct(AcademyContentService $contentService)
    {
        parent::__construct();
        $this->contentService = $contentService;
    }

    public function index()
    {
        $contents = $this->contentService->getForIndex(
            $this->paginationLimit
        );

        return AcademyContentResource::collection($contents);
    }

    public function store(AcademyContentRequest $request)
    {
        $content = $this->contentService->store($request->validated());

        return new AcademyContentResource($content);
    }

    public function show($contentId)
    {
        $content = $this->contentService->findOrFail($contentId);

        return new AcademyContentResource($content);
    }

    public function update(AcademyContentRequest $request, $contentId)
    {
        $content = $this->contentService->update($contentId, $request->validated());


        return new AcademyContentResource($content);
    }

    public function destroy($contentId)
    {
        $content = $this->contentService->delete($contentId);

        return response('success');
    }

    public function search(Request $request)
    {
        return AcademyContentResource::collection($this->contentService->query()
        ->where('title', 'LIKE', $request->name . '%')
        ->orWhere('video_url', 'LIKE', $request->name . '%')
        ->orWhere('description', 'LIKE', $request->name . '%')
        ->orWhere('fors', 'LIKE', $request->name . '%')
        ->paginate($this->paginationLimit));
    }
}