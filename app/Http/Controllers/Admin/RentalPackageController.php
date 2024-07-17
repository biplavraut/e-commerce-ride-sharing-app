<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RentalPackageRequest;
use App\Http\Resources\Admin\RentalPackageResource;
use App\Imports\RentalPackageImport;
use App\Services\RentalPackageService;
use Excel;
use Illuminate\Http\Request;

class RentalPackageController extends CommonController
{
    /** @var RentalPackageService */
    private $rentalPackageService;

    public function __construct(RentalPackageService $rentalPackageService)
    {
        parent::__construct();
        $this->rentalPackageService = $rentalPackageService;
    }

    public function index()
    {
        $rentalPackages = $this->rentalPackageService->getForIndex(
            $this->paginationLimit
        );

        return RentalPackageResource::collection($rentalPackages);
    }

    public function store(RentalPackageRequest $request)
    {
        $rentalPackage = $this->rentalPackageService->store($request->validated());

        return new RentalPackageResource($rentalPackage);
    }

    public function show($rentalPackageId)
    {
        $rentalPackage = $this->rentalPackageService->findOrFail($rentalPackageId);

        return new RentalPackageResource($rentalPackage);
    }

    public function update(RentalPackageRequest $request, $rentalPackageId)
    {
        $rentalPackage = $this->rentalPackageService->update($rentalPackageId, $request->validated());

        return new RentalPackageResource($rentalPackage);
    }

    public function destroy($rentalPackageId)
    {
        $rentalPackage = $this->rentalPackageService->delete($rentalPackageId);

        return response('success');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        Excel::import(new RentalPackageImport, request()->file('import_file'));

        return response('success');
    }
    
    public function search(Request $request)
    {
        return RentalPackageResource::collection($this->rentalPackageService->query()->where('name', 'LIKE', '%'.$request->name.'%')->take(10)->get());
    }
}
