<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use App\Category;
use Carbon\Carbon;
use App\openingTiming;
use Illuminate\Http\Request;
use App\VendorOptionCategory;
use App\Mail\VendorVerifyEmail;
use App\Services\VendorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Custom\Helper\EmailValidator;
use App\Services\VendorOptionService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\VendorRequest;
use App\Http\Resources\Admin\VendorDetailResource;
use App\Http\Resources\Admin\VendorResource;
use App\Http\Resources\Admin\VendorListResource;
use App\Http\Resources\Admin\VendorOptionCategoryResource;

class VendorController extends CommonController
{
    /**
     * @var VendorService
     */
    private $vendorService;

    /** @var VendorOptionService */
    private $vendorOptionService;

    public function __construct(VendorService $vendorService,  VendorOptionService $vendorOptionService)
    {
        parent::__construct();
        $this->vendorService          = $vendorService;
        $this->vendorOptionService     =   $vendorOptionService;
    }

    public function index(Request $request)
    {
        $hidden = $request->isHidden == 'true';
        $status = $request->isInActive == 'true';
        // return $this->vendorService->query()->latest()->paginate($this->paginationLimit);
        switch ($request) {
            case $hidden && $status:
                return VendorResource::collection($this->vendorService->query()->where('is_hidden', 1)->where('status', 0)->latest()->paginate($this->paginationLimit));
                break;
            case $hidden:
                return VendorResource::collection($this->vendorService->query()->where('is_hidden', 1)->latest()->paginate($this->paginationLimit));
                break;
            case $status:
                return VendorResource::collection($this->vendorService->query()->where('status', 0)->latest()->paginate($this->paginationLimit));
                break;
            default:
                return VendorResource::collection($this->vendorService->query()->latest()->paginate($this->paginationLimit));
        }
    }

    public function store(VendorRequest $request)
    {

        if ($request->email) {
            $response = new EmailValidator($request->email);
            if (!$response->validate()) {
                return response()->json(['message' => 'The given data was invalid', 'errors' => ['email' => ['Email is invalid.']]], 422);
            }
        }

        $data = $request->validated();

        $vendor = $this->vendorService->store(array_except($data, ['service_id', 'opening_time_form', 'vendor_option_categories']));
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

        $vendor->update(['email_token' => str_random(10)]);

        $vendor->services()->sync($request->service_id);


        try {
            // Mail::to($vendor->email)->send(new VendorVerifyEmail($vendor->email_token));
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $this->vendorOptionService->saveAndSync($vendor, array_only($data, 'vendor_option_categories')['vendor_option_categories'] ?? []);
        } catch (\Throwable $th) {
            //throw $th;
        }

        foreach ($vendor->services as $key => $service) {
            Redis::set('layoutUpdate_' . $service->id, json_encode($vendor));
        }


        return new VendorResource($vendor);
    }

    public function show($vendorId)
    {
        $vendor = $this->vendorService->findOrFail($vendorId);

        return new VendorDetailResource($vendor);
    }


    public function update(VendorRequest $request, $vendorId)
    {

        if ($request->email) {
            $response = new EmailValidator($request->email);
            if (!$response->validate()) {
                return response()->json(['message' => 'The given data was invalid', 'errors' => ['email' => ['Email is invalid.']]], 422);
            }
        }

        $data = $request->validated();


        $vendor = $this->vendorService->update($vendorId, array_except($data, ['service_id', 'opening_time_form', 'vendor_option_categories']));

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

        // $vendor->services()->sync($request->service_id);


        try {
            $this->vendorOptionService->saveAndSync($vendor, array_only($data, 'vendor_option_categories')['vendor_option_categories'] ?? []);
        } catch (\Throwable $th) {
            //throw $th;
        }

        foreach ($vendor->services as $key => $service) {
            Redis::set('layoutUpdate_' . $service->id, json_encode($vendor));
        }



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
            DB::insert('insert into category_vendor (category_id, vendor_id, created_at, updated_at) values (' . $service . ', ' . $request->id . ', now(),now())');
        }

        $vendor->update(['settlement_time' => $request->settlement]);
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
            $category = Category::findOrFail($request->id ? $request->id : $request->key);
            $hidden = $request->isHidden == 'true';
            $status = $request->isInActive == 'true';
            // return $this->vendorService->query()->latest()->paginate($this->paginationLimit);
            switch ($request) {
                case $hidden && $status:
                    $data = $category->registeredVendor()->where('is_hidden', 1)->where('status', 0)->latest()->paginate($this->paginationLimit);
                    break;
                case $hidden:
                    $data = $category->registeredVendor()->where('is_hidden', 1)->latest()->paginate($this->paginationLimit);
                    break;
                case $status:
                    $data = $category->registeredVendor()->where('status', 0)->latest()->paginate($this->paginationLimit);
                    break;
                default:
                    $data = $category->registeredVendor()->latest()->paginate($this->paginationLimit);
            }


            $data->appends(['id' => $request->id ? $request->id : $request->key])->links();
            return VendorResource::collection($data)->additional(['meta' => [
                'key' => $request->id,
            ]]);
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

    public function options(Request $request)
    {
        if ($request->vendorId) {
            $vendor = $this->vendorService->findOrFail($request->vendorId);
            $serviceIds = $vendor->services()->pluck('category_id');
            return VendorOptionCategoryResource::collection(VendorOptionCategory::whereIn('service_id', $serviceIds)->orderBy('order')->get());
        } else if ($request->serviceId) {
            return VendorOptionCategoryResource::collection(VendorOptionCategory::where('service_id', $request->serviceId)->orderBy('order')->get());
        }

        return VendorOptionCategoryResource::collection(VendorOptionCategory::orderBy('order')->get());
    }
}
