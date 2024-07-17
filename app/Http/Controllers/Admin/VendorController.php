<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use App\Category;
use Carbon\Carbon;
use App\openingTiming;
use Illuminate\Http\Request;
use App\Mail\VendorVerifyEmail;
use App\Services\VendorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Vendor\VendorResource;
use App\Http\Requests\Api\VendorRegisterRequest;
use App\Http\Resources\Admin\VendorListResource;

class VendorController extends CommonController
{
    /**
     * @var VendorService
     */
    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        parent::__construct();
        $this->vendorService          = $vendorService;
    }

    public function index()
    {
        return VendorResource::collection($this->vendorService->query()->orderBY('verified')->paginate($this->paginationLimit));
        // $vendors = $this->vendorService->getForIndex(
        //     $this->paginationLimit
        // );
        // return VendorResource::collection($vendors);
    }

    public function store(Request $request)
    {
        $vendor = $this->vendorService->store($request->except(['service_id', 'opening_time_form']));
        $openTiming = $request->opening_time_form;
        //Manage data for timing
        $openTimingData = [
            'vendor_id' =>   $vendor->id,
            'sun_opening' => $openTiming[0]['status'] == true ? $openTiming[0]['opentime'] : null,
            'sun_closing' => $openTiming[0]['status'] == true ? $openTiming[0]['closetime'] : null,
            'mon_opening' => $openTiming[1]['status'] == true ? $openTiming[1]['opentime'] : null,
            'mon_closing' => $openTiming[1]['status'] == true ? $openTiming[1]['closetime'] : null,
            'tue_opening' => $openTiming[2]['status'] == true ? $openTiming[2]['opentime'] : null,
            'tue_closing' => $openTiming[2]['status'] == true ? $openTiming[2]['closetime'] : null,
            'wed_opening' => $openTiming[3]['status'] == true ? $openTiming[3]['opentime'] : null,
            'wed_closing' => $openTiming[3]['status'] == true ? $openTiming[3]['closetime'] : null,
            'thu_opening' => $openTiming[4]['status'] == true ? $openTiming[4]['opentime'] : null,
            'thu_closing' => $openTiming[4]['status'] == true ? $openTiming[4]['closetime'] : null,
            'fri_opening' => $openTiming[5]['status'] == true ? $openTiming[5]['opentime'] : null,
            'fri_closing' => $openTiming[5]['status'] == true ? $openTiming[5]['closetime'] : null,
            'sat_opening' => $openTiming[6]['status'] == true ? $openTiming[6]['opentime'] : null,
            'sat_closing' => $openTiming[6]['status'] == true ? $openTiming[6]['closetime'] : null,
            'created_at' => date("Y=m-d H:i:s"),
            'updated_at' => date("Y=m-d H:i:s"),
        ];



        try {
            openingTiming::insert($openTimingData);
        } catch (\Throwable $th) {
            //throw $th;
        }

        $vendor->update(['email_token' => str_random(10)]);

        $vendor->services()->sync($request->service_id);

        try {
            Mail::to($vendor->email)->send(new VendorVerifyEmail($vendor->email_token));
        } catch (\Throwable $th) {
            //throw $th;
        }

        return new VendorResource($vendor);
    }

    public function show($vendorId)
    {
        $vendor = $this->vendorService->findOrFail($vendorId);

        return new VendorResource($vendor);
    }


    public function update(Request $request, $vendorId)
    {
        $vendor = $this->vendorService->update($vendorId, $request->except(['_method', 'service_id', 'opening_time_form']));

        if ($vendor->scheduleTime) {
            $vendor->scheduleTime->delete();
        }


        $openTiming = $request->opening_time_form;
        //Manage data for timing
        $openTimingData = [
            'vendor_id' =>   $vendor->id,
            'sun_opening' => $openTiming[0]['status'] == "true" ? $openTiming[0]['opentime'] : null,
            'sun_closing' => $openTiming[0]['status'] == "true" ? $openTiming[0]['closetime'] : null,
            'mon_opening' => $openTiming[1]['status'] == "true" ? $openTiming[1]['opentime'] : null,
            'mon_closing' => $openTiming[1]['status'] == "true" ? $openTiming[1]['closetime'] : null,
            'tue_opening' => $openTiming[2]['status'] == "true" ? $openTiming[2]['opentime'] : null,
            'tue_closing' => $openTiming[2]['status'] == "true" ? $openTiming[2]['closetime'] : null,
            'wed_opening' => $openTiming[3]['status'] == "true" ? $openTiming[3]['opentime'] : null,
            'wed_closing' => $openTiming[3]['status'] == "true" ? $openTiming[3]['closetime'] : null,
            'thu_opening' => $openTiming[4]['status'] == "true" ? $openTiming[4]['opentime'] : null,
            'thu_closing' => $openTiming[4]['status'] == "true" ? $openTiming[4]['closetime'] : null,
            'fri_opening' => $openTiming[5]['status'] == "true" ? $openTiming[5]['opentime'] : null,
            'fri_closing' => $openTiming[5]['status'] == "true" ? $openTiming[5]['closetime'] : null,
            'sat_opening' => $openTiming[6]['status'] == "true" ? $openTiming[6]['opentime'] : null,
            'sat_closing' => $openTiming[6]['status'] == "true" ? $openTiming[6]['closetime'] : null,
            'created_at' => date("Y=m-d H:i:s"),
            'updated_at' => date("Y=m-d H:i:s"),
        ];
        try {
            openingTiming::insert($openTimingData);
        } catch (\Throwable $th) {
            //throw $th;
        }

        // dd($request->opening_time_form);

        return new VendorResource($vendor);
    }



    //Verify Vendor and sync the service vendor provides
    public function verify(Request $request)
    {
        $vendor = $this->vendorService->findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'service' => 'required'
        ]);

        if ($validator->fails()) {
            return response('Please select service(s) to verify.');
        }

        $services = explode(",", $request->service);
        foreach ($services as $key => $service) {
            // $vendor->services->create(['category_id' => $service]);
            DB::insert('insert into category_vendor (category_id, vendor_id, created_at, updated_at) values (' . $service . ', ' . $request->id . ', now(),now())');
        }

        $vendor->verify();

        return response('success');
    }

    public function getVendor(Request $request)
    {
        return $this->vendorService->getAdvancedVendors($request->name);
    }

    public function byService(Request $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            return VendorResource::collection($category->registeredVendor()->get());
        } catch (\Throwable $th) {
            return response('error');
        }
    }

    public function destroy($vendorId)
    {
        $vendor = $this->vendorService->delete($vendorId);

        return response('success');
    }

    public function getVendorList()
    {
        $vendors = $this->vendorService->query()->where('verified', 1)->select('id', 'business_name', 'type')->get();
        return VendorListResource::collection($vendors);
    }

    public function actionPerform($id = 0)
    {
        $vendor = Vendor::findOrFail($id);
        $status = $vendor->is_hidden == 1 ? 0 : 1;
        $vendor->update(['is_hidden' => $status]);
        return response('success');
    }

    public function disableVendor($id = 0)
    {
        $vendor = Vendor::findOrFail($id);
        $status = $vendor->status == 1 ? 0 : 1;
        $vendor->update(['status' => $status]);
        return response('success');
    }

    public function openClose($vendorId)
    {
        $vendor = $this->vendorService->findOrFail($vendorId);
        $time = $vendor->scheduleTime;
        if ($time) {
            return response()->json([
                [
                    'opentime' => ($time->sun_opening),
                    "closetime" => ($time->sun_closing),
                    'status' => $time->sun_opening != null
                ],
                [
                    'opentime' => ($time->mon_opening),
                    "closetime" => ($time->mon_closing),
                    'status' => $time->mon_opening != null
                ],
                [
                    'opentime' => ($time->tue_opening),
                    "closetime" => ($time->tue_closing),
                    'status' => $time->tue_opening != null
                ],
                [
                    'opentime' => ($time->wed_opening),
                    "closetime" => ($time->wed_closing),
                    'status' => $time->wed_opening != null
                ],
                [
                    'opentime' => ($time->thu_opening),
                    "closetime" => ($time->thu_closing),
                    'status' => $time->thu_opening != null
                ],
                [
                    'opentime' => ($time->fri_opening),
                    "closetime" => ($time->fri_closing),
                    'status' => $time->fri_opening != null
                ],
                [
                    'opentime' => ($time->sat_opening),
                    "closetime" => ($time->sat_closing),
                    'status' => $time->sat_opening != null
                ],

            ]);
        }

        return response()->json([
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],
            [
                'opentime' => null,
                "closetime" => null,
                'status' => false
            ],

        ]);
    }
}
