<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\VendorService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VendorDiscountResource;
use App\Services\VendorDiscountService;
use App\VendorDiscount;

class VendorDiscountController extends CommonController
{

    /** @var VendorService */
    private $vendorService;

    /** @var VendorDiscountService */
    private $vendorDiscountService;


    public function __construct(VendorService $vendorService, VendorDiscountService $vendorDiscountService)
    {
        parent::__construct();
        $this->vendorService = $vendorService;
        $this->vendorDiscountService        = $vendorDiscountService;
    }
    public function index()
    {
        $discounts = $this->vendorDiscountService->getForIndex(
            $this->paginationLimit
        );
        return VendorDiscountResource::collection($discounts);
    }

    public function store(Request $request)
    {
        $exist = VendorDiscount::where('vendor_id', $request->vendor_id)->first();

        if ($exist) {
            return response()->json(['status' =>false, 'message' => 'Vendor Exist in this listed.']);
        }
        
        $add = VendorDiscount::create($request->all());
        return response()->json(['status' => true, 'message' => 'Successfully Added.']);
    }

    public function findVendor(Request $request)
    {
        $existing_vendors = $this->vendorDiscountService->query()->latest()->pluck('vendor_id');
        
        $vendors = $this->vendorService->query()->select('id', 'business_name', 'phone')
            ->where('business_name', 'LIKE', '%'. $request->q . '%')
            ->whereNotIn('id', $existing_vendors)
            ->limit(10)
            ->get();

        return $vendors;
    }

    public function destroy($vendorDiscountId)
    {
        $discount = VendorDiscount::where('id', $vendorDiscountId)->firstOrFail();
        if ($discount) {
            $discount->delete();
        }
        return response('success');
    }
}
