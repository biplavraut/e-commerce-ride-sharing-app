<?php

namespace App\Http\Controllers\Api\Ride;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Ride\PopularPlaceResource;
use App\Http\Resources\Api\Ride\PremiumPlaceResource;
use App\Http\Resources\Api\Ride\RentalPackageResource;
use App\Http\Resources\Api\Ride\RidingFareResource;
use App\Services\PremiumPlaceService;
use App\Services\RentalPackageService;
use App\Services\RidingFareService;
use Illuminate\Http\Request;

class CabController extends Controller
{
    /** @var PremiumPlaceService */
    private $premiumPlaceService;

    /** @var RidingFareService */
    private $ridingFareService;

    /** @var RentalPackageService */
    private $rentalPackageService;

    public function __construct(PremiumPlaceService $premiumPlaceService, RidingFareService $ridingFareService, RentalPackageService $rentalPackageService)
    {
        $this->premiumPlaceService = $premiumPlaceService;
        $this->ridingFareService = $ridingFareService;
        $this->rentalPackageService = $rentalPackageService;
    }

    public function premiumPlace(Request $request)
    {
        if ($request->location) {
            $places = $this->premiumPlaceService->getPlaces($request->location);
            return PremiumPlaceResource::collection($places)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }
    }

    public function popularPlaces(Request $request)
    {
        if ($request->location) {
            $places = $this->premiumPlaceService->getPopularPlaces($request->location);
            return PopularPlaceResource::collection($places)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }

        $places = $this->premiumPlaceService->query()->where('popular', 1)->where('hide', 0)->latest()->get();
        return PopularPlaceResource::collection($places)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function ridingFares(Request $request)
    {
        if ($request->name) {
            $fares = $this->ridingFareService->getFares($request->name);
            return RidingFareResource::collection($fares)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }

        $fares = $this->ridingFareService->query()->latest()->get();
        return RidingFareResource::collection($fares)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function rentalPackages(Request $request)
    {
        if ($request->name) {
            $fares = $this->rentalPackageService->getPackages($request->name);
            return RentalPackageResource::collection($fares)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        }

        $fares = $this->rentalPackageService->query()->latest()->get();
        return RentalPackageResource::collection($fares)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
