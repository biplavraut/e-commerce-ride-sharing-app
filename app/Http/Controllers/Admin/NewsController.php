<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NewsRequest;
use App\Http\Resources\Admin\NewsResource;
use App\Services\NewsService;

class NewsController extends CommonController
{
	/** @var NewsService */
	private $newsService;

	public function __construct(NewsService $newsService)
	{
		parent::__construct();
		$this->newsService = $newsService;
	}

	public function index()
	{
		$news = $this->newsService->getLatestPaginatedList(
			$this->paginationLimit, ['id', 'name', 'image', 'created_at']
		);

		return NewsResource::collection($news);
	}

	public function store(NewsRequest $request)
	{
		$news = $this->newsService->store();

		return new NewsResource($news);
	}

	public function show($newsId)
	{
		$news = $this->newsService->findOrFail($newsId, ['id', 'name', 'slug', 'image', 'description']);
		return new NewsResource($news);
	}

	public function update(NewsRequest $request, $newsId)
	{
		$news = $this->newsService->update($newsId);

		return new NewsResource($news);
	}

	public function destroy($newsId)
	{
		$news = $this->newsService->delete($newsId);
		$news->deleteImage();

		return response('success');
	}

	public function deleteMultiple()
	{
		$ids = request()->input('ids');

		$this->newsService->findOrFail($ids)->map(function ($news) {
			$news->deleteImage();
		});

		$data = $this->newsService->deleteMultiple($ids);

		return response($data);
	}

}
