<?php

namespace App\Http\Controllers\Admin;
use App\Services\ItemsService;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\ItemsResource;
use App\Http\Requests\Admin\ItemsRequest;

class ItemsController extends CommonController
{
    /** @var ItemsService */
    private $ItemsService;

    public function __construct(ItemsService $ItemsService)
    {
        parent::__construct();
        $this->ItemsService = $ItemsService;
    }

    public function index()
    {
        $sendItems = $this->ItemsService->getForIndex(
            $this->paginationLimit
        );
        return ItemsResource::collection($sendItems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemsRequest $request)
    {
        $sendItem = $this->ItemsService->store();
		return new ItemsResource($sendItem);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sendItem = $this->ItemsService->findOrFail($id, ['id', 'name', 'flat_price', 'added_per_km_price','added_weightprice_per_kg','status']);
		return new ItemsResource($sendItem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemsRequest $request, $id)
    {
        $sendItems = $this->ItemsService->update($id);
		return new ItemsResource($sendItems);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sendItems = $this->ItemsService->delete($id);
		return response('success');
    }
}
