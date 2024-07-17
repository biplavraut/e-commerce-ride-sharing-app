<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TestimonialRequest;
use App\Http\Resources\Admin\TestimonialResource;
use App\Services\TestimonialService;

class TestimonialController extends CommonController
{
	private $testimonialService;

	public function __construct(TestimonialService $testimonialService)
	{
		parent::__construct();
		$this->testimonialService = $testimonialService;
	}

	public function index()
	{
		$testimonials = $this->testimonialService->getLatestPaginatedList(
			$this->paginationLimit, ['id', 'name', 'image', 'message']
		);

		return TestimonialResource::collection($testimonials);
	}

	public function store(TestimonialRequest $request)
	{
		$model = $this->testimonialService->store();

		return new TestimonialResource($model);
	}

	public function show($testimonialId)
	{
		$testimonial = $this->testimonialService->findOrFail($testimonialId);

		return new TestimonialResource($testimonial);
	}

	public function update(TestimonialRequest $request, $testimonialId)
	{
		$model = $this->testimonialService->update($testimonialId);

		return new TestimonialResource($model);
	}

	public function destroy($testimonialId)
	{
		$testimonial = $this->testimonialService->delete($testimonialId);
		$testimonial->deleteImage();

		return response()->json(['message' => 'Testimonial successfully deleted.']);
	}

	public function deleteMultiple()
	{
		$ids = request()->input('ids');

		$this->testimonialService->findOrFail($ids)->map(function ($testimonial) {
			$testimonial->deleteImage();
		});

		$data = $this->testimonialService->deleteMultiple($ids);

		return response($data);
	}

}
