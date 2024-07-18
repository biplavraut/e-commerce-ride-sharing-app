<?php

namespace App\Http\Controllers\Admin;

use App\Deal;
use DateTime;
use Carbon\Carbon;
use App\DealProduct;
use Illuminate\Http\Request;
use App\Services\DealService;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Services\DealProductService;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\Admin\DealRequest;
use App\Http\Resources\Admin\DealResource;
use App\Http\Resources\Admin\DealProductResource;

class DealController extends CommonController
{

    /** @var DealService */
    private $dealService;


    /** @var DealProductService */
    private $dealProductService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(DealService $dealService, DealProductService $dealProductService, ProductService $productService)
    {
        parent::__construct();
        $this->dealService = $dealService;
        $this->productService        = $productService;
        $this->dealProductService        = $dealProductService;
    }
    public function index()
    {
        //
        $deals = $this->dealService->getForIndex(
            $this->paginationLimit
        );
        return DealResource::collection($deals);
    }

    public function store(DealRequest $request)
    {
        //
        // Initialising the two datetime objects
        $from = new DateTime($request->from);
        $to = new DateTime($request->to);

        $from = strtotime($from->format("Y-m-d H:i:s"));
        $to = strtotime($to->format("Y-m-d H:i:s"));

        $date_diff = $to - $from;
        $strDeltaTime = "" . $date_diff / 60 / 60; // sec -> hour
        if ($strDeltaTime <= 0) {
            $request->merge(['to' => '']);
        }
        $this->validate($request, [
            'to' => 'required'
        ], ['to.required' => 'The To Date-Time is required and must be greater then From Date-Time']);

        $deal = $this->dealService->store();
        Redis::set('layoutUpdate_' . $deal->category_id, json_encode($deal));
        return new DealResource($deal);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $deal = Deal::findOrFail($id);

        return new DealResource($deal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DealRequest $request, $id)
    {
        //
        // Initialising the two datetime objects
        $from = new DateTime($request->from);
        $to = new DateTime($request->to);

        $from = strtotime($from->format("Y-m-d H:i:s"));
        $to = strtotime($to->format("Y-m-d H:i:s"));

        $date_diff = $to - $from;
        $strDeltaTime = "" . $date_diff / 60 / 60; // sec -> hour
        if ($strDeltaTime <= 0) {
            $request->merge(['to' => '']);
        }
        $this->validate($request, [
            'to' => 'required'
        ], ['to.required' => 'The To Date-Time is required and must be greater then From Date-Time']);

        $deal = $this->dealService->update($id, $request->validated());
        Redis::set('layoutUpdate_' . $deal->category_id, json_encode($deal));

        return new DealResource($deal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // $deal = $this->dealService->query()->firstOrFail();
        // $dealImage = $deal->image;
        $delete = $this->dealService->delete($id);
        // if($delete){
        //     if(file_exists($$dealImage)){
        //         unlink($$dealImage);
        //     }
        // }
        return response('success');
    }

    public function search(Request $request)
    {
        return DealResource::collection($this->dealService->query()->where('title', 'LIKE', '%' . $request->title . '%')->orWhere('sub_title', 'LIKE', '%' . $request->title . '%')->paginate($this->paginationLimit));
    }

    public function loadDealDetail(Request $request)
    {
        $products = DealProduct::where('deal_id', $request->id)
            ->orderBy('order')
            ->with('product')
            ->get();
        return DealProductResource::collection($products);
    }

    public function findProduct(Request $request)
    {
        $deal_id = $request->deal;
        $service_id = $this->dealService->query()->select('category_id')
            ->where('id', $deal_id)
            ->first();
        $active_deals_in_service = $this->dealService->query()
            ->where('category_id', $service_id->category_id)
            ->where('status', 1)
            ->pluck('id');
        $existing_products = DealProduct::whereIn('deal_id', $active_deals_in_service)->pluck('product_id');
        
        $product = $this->productService->query()->select('id', 'vendor_id', 'title', 'price', 'product_category_id')
            ->whereHas('category')
            ->where('title', 'LIKE', '%'. $request->q . '%')
            ->where('hide', 0)->Where('verified', 1)
            ->whereNotIn('id', $existing_products)
            ->limit(10)
            ->with(array('vendor' => function ($query) {
                $query->select('id', 'business_name');
            }))->get()->filter(function ($item) use ($service_id) {
                return $item->service->id == $service_id->category_id;
            });

        return $product;
    }

    public function addProduct(Request $request)
    {
        $add = DealProduct::create($request->all());
        return response()->json(['status' => true, 'message' => 'Successfully Added.']);
    }

    public function deleteDealProduct($id)
    {
        $product = DealProduct::where('id', $id)->firstOrFail();
        if ($product) {
            $product->delete();
        }
        return response('success');
    }

    public function changeOrder()
    {
        $this->dealProductService->changeOrder(request()->all());
    }
}
