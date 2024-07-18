<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\PartnerService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartnerResource;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class PartnerController extends CommonController
{
    /**
     * @var PartnerService
     */
    private $partnerService;


    public function __construct(PartnerService $partnerService)
    {
        $this->partnerService = $partnerService;
    }
    public function index(Request $request)
    {
        $partners = $this->partnerService->query()->where('hide', 0)->where('parent_id', NULL)->orderBy('expire_in', 'DESC')->orderBy('order')->paginate(10);
        return PartnerResource::collection($partners)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);

        $finalPartners = [];
        $expired = [];

        foreach ($partners as $key => $partner) {
            if ($partner->expire_in) {

                if (Carbon::parse($partner->expire_in)->gte(Carbon::now())) {
                    $finalPartners[] = $partner;
                } else {
                    $expired[] = $partner;
                }
            } else {
                $finalPartners[] = $partner;
            }
        }
        $finalPartners = collect($finalPartners)->merge($expired);

        $page = $request->page ?? 1;

        $data = array_slice($finalPartners->toArray(), $page == 1 ? 0 : ($page * $this->paginationLimit) - $this->paginationLimit, $page == 1 ? $this->paginationLimit :  $this->paginationLimit * $page);


        $paginator = new Paginator($data, $finalPartners->count(), $this->paginationLimit,  $page, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        return PartnerResource::collection($paginator)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }

    public function branches(Request $request)
    {
        $partners = $this->partnerService->query()->where('hide', 0)->where('parent_id', $request->parentId)->orWhere('id', $request->parentId)->orderBy('expire_in', 'DESC')->orderBy('order')->paginate(10);
        return PartnerResource::collection($partners)->additional(['status' => true, 'message' => "", 'statusCode' => 200], 200);
    }
}
