<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Services\SliderService;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\Admin\SliderRequest;
use App\Http\Resources\Admin\SliderResource;

class SliderController extends CommonController
{
    /** @var SliderService */
    private $sliderService;

    public function __construct(SliderService $sliderService)
    {
        parent::__construct();
        $this->sliderService = $sliderService;
    }

    public function index()
    {
        $sliders = $this->sliderService->getForIndex(
            $this->paginationLimit
        );

        return SliderResource::collection($sliders);
    }

    public function store(SliderRequest $request)
    {
        $slider = $this->sliderService->store($request->validated());

        try {
            Redis::set('layoutUpdate_' . $slider->category_id, json_encode($slider));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new SliderResource($slider);
    }

    public function show($sliderId)
    {
        $slider = $this->sliderService->findOrFail($sliderId);

        return new SliderResource($slider);
    }

    public function update(SliderRequest $request, $sliderId)
    {
        $slider = $this->sliderService->update($sliderId, $request->validated());

        try {
            Redis::set('layoutUpdate_' . $slider->category_id, json_encode($slider));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new SliderResource($slider);
    }

    public function destroy($sliderId)
    {
        $slider = $this->sliderService->delete($sliderId);

        return response('success');
    }

    public function byService(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            return SliderResource::collection($this->sliderService->query()->where('category_id', $category->id)->get());
        } catch (\Throwable $th) {
            return response('error');
        }
    }

    public function search(Request $request)
    {
        return SliderResource::collection($this->sliderService->query()->where('name', 'LIKE', '%' . $request->name . '%')->paginate($this->paginationLimit));
    }
}
