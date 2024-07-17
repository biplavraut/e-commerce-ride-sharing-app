<?php

namespace App\Http\Controllers\Admin;

use App\Caches\SocialCache;
use App\Http\Requests\Admin\SocialRequest;
use App\Http\Resources\Admin\SocialResource;
use App\Services\SocialService;

class SocialController extends CommonController
{
	protected $cacheKey = SocialCache::CACHE_KEY;

	private $socialService;

	public function __construct(SocialService $socialService)
	{
		parent::__construct();
		$this->socialService = $socialService;
	}

	public function index()
	{
		$socials = resolve('social.cache')->getForIndex($this->paginationLimit);

		return SocialResource::collection($socials);
	}

	public function store(SocialRequest $request)
	{
		$social = $this->socialService->store($request->validated());

		$this->forgetIndexCache();

		return new SocialResource($social);
	}

	public function show($socialId)
	{
		$social = $this->socialService->findOrFail($socialId);

		return new SocialResource($social);
	}

	public function update(SocialRequest $request, $socialId)
	{
		$social = $this->socialService->update($socialId, $request->validated());

		$this->forgetIndexCache();

		return new SocialResource($social);
	}

	public function destroy($socialId)
	{
		$social = $this->socialService->delete($socialId);

		$this->forgetIndexCache();

		return response()->json(['id' => $social->id]);
	}

	public function deleteMultiple()
	{
		$ids = request()->input('ids');

		$this->socialService->findOrFail($ids)->map(function ($social) {
			$social->deleteImage('icon');
		});

		$data = $this->socialService->deleteMultiple($ids);

		$this->forgetIndexCache();

		return response($data);
	}

	public function changeOrder()
	{
		$this->socialService->changeOrder(request()->all());

		$this->forgetIndexCache();
	}
}
