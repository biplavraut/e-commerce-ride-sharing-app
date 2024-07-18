<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\DefaultConfResource;
use App\Services\DefaultConfService;
use Illuminate\Http\Request;

class DefaultConfController extends CommonController
{
    /** @var DefaultConfService */
    private $defaultConfService;

    public function __construct(DefaultConfService $defaultConfService)
    {
        parent::__construct();
        $this->defaultConfService = $defaultConfService;
    }

    public function index()
    {
        $conf = $this->defaultConfService->latest();

        return new DefaultConfResource($conf);
    }

    public function store(Request $request)
    {
        $conf = $this->defaultConfService->latest();
        if ($conf) {
            $conf = $this->defaultConfService->update($conf->id, $request->except('_method'));
        } else {
            $conf = $this->defaultConfService->store($request->validated());
        }

        return new DefaultConfResource($conf);
    }

    public function update(Request $request, $confId)
    {
        $conf = $this->defaultConfService->update($confId, $request->validated());

        return new DefaultConfResource($conf);
    }
}
