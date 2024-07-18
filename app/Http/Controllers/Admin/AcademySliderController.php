<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademySliderRequest;
use App\Http\Resources\Admin\AcademySliderResource;
use App\Services\AcademySliderService;

class AcademySliderController extends CommonController
{
    /** @var AcademySliderService */
    private $sliderService;

    public function __construct(AcademySliderService $sliderService)
    {
        parent::__construct();
        $this->sliderService = $sliderService;
    }

    public function index()
    {
        $sliders = $this->sliderService->getForIndex(
            $this->paginationLimit
        );

        return AcademySliderResource::collection($sliders);
    }

    public function store(AcademySliderRequest $request)
    {
        $this->validate($request, ['image' => 'required']);
        $slider = $this->sliderService->store($request->validated());

        return new AcademySliderResource($slider);
    }

    public function show($sliderId)
    {
        $slider = $this->sliderService->findOrFail($sliderId);

        return new AcademySliderResource($slider);
    }

    public function update(AcademySliderRequest $request, $sliderId)
    {
        $slider = $this->sliderService->update($sliderId, $request->validated());


        return new AcademySliderResource($slider);
    }

    public function destroy($sliderId)
    {
        $slider = $this->sliderService->delete($sliderId);

        return response('success');
    }

    public function search(Request $request)
    {
        return AcademySliderResource::collection($this->sliderService->query()
        ->where('name', 'LIKE', $request->name . '%')
        ->orWhere('fors', 'LIKE', $request->name . '%')
        ->paginate($this->paginationLimit));
    }
}