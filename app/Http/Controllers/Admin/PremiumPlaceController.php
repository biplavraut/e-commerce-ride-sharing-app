<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PremiumPlaceRequest;
use App\Http\Resources\Admin\PremiumPlaceResource;
use App\Imports\PremiumPlaceImport;
use App\Services\PremiumPlaceService;
use Excel;
use Illuminate\Http\Request;

class PremiumPlaceController extends CommonController
{
    /** @var PremiumPlaceService */
    private $premiumPlaceService;

    public function __construct(PremiumPlaceService $premiumPlaceService)
    {
        parent::__construct();
        $this->premiumPlaceService = $premiumPlaceService;
    }

    public function index()
    {
        $premiumPlaces = $this->premiumPlaceService->getForIndex(
            $this->paginationLimit
        );

        return PremiumPlaceResource::collection($premiumPlaces);
    }

    public function store(PremiumPlaceRequest $request)
    {
        $premiumPlace = $this->premiumPlaceService->store($request->validated());

        return new PremiumPlaceResource($premiumPlace);
    }

    public function show($premiumPlaceId)
    {
        $premiumPlace = $this->premiumPlaceService->findOrFail($premiumPlaceId);

        return new PremiumPlaceResource($premiumPlace);
    }

    public function update(PremiumPlaceRequest $request, $premiumPlaceId)
    {
        $premiumPlace = $this->premiumPlaceService->update($premiumPlaceId, $request->validated());

        return new PremiumPlaceResource($premiumPlace);
    }

    public function destroy($premiumPlaceId)
    {
        $premiumPlace = $this->premiumPlaceService->delete($premiumPlaceId);

        return response('success');
    }
    
    public function getPlaces(Request $request)
    {
        $places = $this->premiumPlaceService->getPlaces($request->name);

        return PremiumPlaceResource::collection($places);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        Excel::import(new PremiumPlaceImport, request()->file('import_file'));

        return response('success');
    }

    public function search(Request $request)
    {
        return PremiumPlaceResource::collection($this->premiumPlaceService->query()->where('location', 'LIKE', $request->name.'%')->take(10)->get());
    }
}
