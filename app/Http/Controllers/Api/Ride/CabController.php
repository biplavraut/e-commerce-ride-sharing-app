<?php

namespace App\Http\Controllers\Api\Ride;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\RidingFareService;
use App\Http\Controllers\Controller;
use App\Services\PremiumPlaceService;
use App\Services\RentalPackageService;
use App\Http\Resources\Api\Ride\RidingFareResource;
use App\Http\Resources\Api\Ride\PopularPlaceResource;
use App\Http\Resources\Api\Ride\PremiumPlaceResource;
use App\Http\Resources\Api\Ride\RentalPackageResource;

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
        // if ($request->name) {
        //     $fares = $this->ridingFareService->getFares($request->name);
        //     return RidingFareResource::collection($fares)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
        // }

        $rawFares = $this->ridingFareService->query()->latest()->get();
        // $fares = collect([]);

        $finalFares = [];


        foreach ($rawFares as $key => $fare) {

            if (!$request->toLat && $fare->surges()->count() < 1) {
                $finalFares[] = $fare;
            }

            if ($request->toLat && $fare->surges()->count() == 0) {
                $finalFares[] = $this->locationSurge($fare, $request);
            }

            if ($request->toLat && $fare->surges()->count() > 0) {
                $finalFares[] = $this->timeAndLocationSurges($fare, $request);
            }

            if (!$request->toLat && $fare->surges()->count() > 0) {
                $finalFares[] = $this->locationSurges($fare, $request);
            }
        }

        return RidingFareResource::collection(collect($finalFares))->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
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

    public function locationSurge($fare, $request)
    {
        $premiumPlaces = $this->premiumPlaceService->query()->where('hide', 0)->where('popular', 0)->get();

        foreach ($premiumPlaces as $key => $place) {
            $distance =  number_format((float) getDistance($place->lat, $place->long, $request->toLat, $request->toLong), 2, '.', '');
            if ($distance <= $place->radius) {
                $fare['addonSurge'] = $place->price;
            }
        }
        return $fare;
    }

    public function timeAndLocationSurges($fare, $request)
    {

        $premiumPlaces = $this->premiumPlaceService->query()->where('hide', 0)->where('popular', 0)->get();

        $activeSurges = $fare->surges()->where('from', '<=', date('Y-m-d H:i:s'))->Where('to', '>=', date('Y-m-d H:i:s'))->get();

        foreach ($premiumPlaces as $key => $place) {
            $distance =  number_format((float) getDistance($place->lat, $place->long, $request->toLat, $request->toLong), 2, '.', '');
            if ($distance <= $place->radius) {
                $fare['addonSurge'] = $place->price;
            } else {
                $fare['addonSurge'] = 0;
            }
        }

        foreach ($activeSurges as $key => $surge) {
            $fare['addonSurge'] = $fare['addonSurge'] + $surge->price;
        }

        return $fare;
    }

    public function locationSurges($fare)
    {
        $activeSurges = $fare->surges()->where('from', '<=', date('Y-m-d H:i:s'))->Where('to', '>=', date('Y-m-d H:i:s'))->get();

        foreach ($activeSurges as $key => $surge) {
            $fare['addonSurge'] = $fare['addonSurge'] + $surge->price;
        }

        return $fare;
    }
}
