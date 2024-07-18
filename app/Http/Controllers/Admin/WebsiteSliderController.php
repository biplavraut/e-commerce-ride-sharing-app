<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Services\WebsiteSliderService;
use App\Http\Requests\Admin\WebsiteSliderRequest;
use App\Http\Resources\Admin\WebsiteSliderResource;

class WebsiteSliderController extends CommonController
{
    /** @var WebsiteSliderService */
    private $sliderService;

    public function __construct(WebsiteSliderService $sliderService)
    {
        parent::__construct();
        $this->sliderService = $sliderService;
    }

    public function index()
    {
        $sliders = $this->sliderService->query()->orderBy('order')->get();

        return WebsiteSliderResource::collection($sliders);
    }

    public function store(WebsiteSliderRequest $request)
    {
        $slider = $this->sliderService->store($request->validated());


        return new WebsiteSliderResource($slider);
    }

    public function show($sliderId)
    {
        $slider = $this->sliderService->findOrFail($sliderId);

        return new WebsiteSliderResource($slider);
    }

    public function update(WebsiteSliderRequest $request, $sliderId)
    {
        $slider = $this->sliderService->update($sliderId, $request->validated());

        return new WebsiteSliderResource($slider);
    }

    public function destroy($sliderId)
    {
        $slider = $this->sliderService->delete($sliderId);

        return response('success');
    }

    public function search(Request $request)
    {
        return WebsiteSliderResource::collection($this->sliderService->query()->where('name', 'LIKE', '%' . $request->name . '%')->orderBy('order')->paginate($this->paginationLimit));
    }

    public function changeOrder()
    {
        $this->sliderService->changeOrder(request()->all());
    }
}
