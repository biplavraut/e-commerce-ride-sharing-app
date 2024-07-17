<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UnitRequest;
use App\Http\Resources\Admin\UnitResource;
use App\Imports\UnitImport;
use App\Services\UnitService;
use App\Unit;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends CommonController
{
    /** @var UnitService */
    private $unitService;

    public function __construct(UnitService $unitService)
    {
        parent::__construct();
        $this->unitService = $unitService;
    }

    public function index()
    {
        $units = $this->unitService->getForIndex(
            $this->paginationLimit
        );

        return UnitResource::collection($units);
    }

    public function store(UnitRequest $request)
    {
        DB::transaction(function () use ($request, &$unit) {
            $data = $request->validated();

            foreach ($request->units as $key => $unit) {
                $unit = Unit::create(['product_category_id' => $request->product_category_id, 'name' => $unit['name']]);
            }

            return new UnitResource($unit);
        });
    }

    public function show($unitId)
    {
        $unit = $this->unitService->findOrFail($unitId);

        return new UnitResource($unit);
    }

    public function update(UnitRequest $request, $unitId)
    {
        $data = $request->validated();
        $unit = $this->unitService->update($unitId, array_except($data, ['units']));

        return new UnitResource($unit);
    }

    public function destroy($unitId)
    {
        $unit = $this->unitService->delete($unitId);

        return response('success');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        Excel::import(new UnitImport, request()->file('import_file'));

        return response('success');
    }

    public function search(Request $request)
    {
        return UnitResource::collection($this->unitService->query()->where('name', 'LIKE', '%'.$request->name.'%')->take(10)->get());
    }
}
