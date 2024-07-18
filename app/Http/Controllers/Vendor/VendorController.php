<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Resources\Vendor\AdminResource;
use App\Services\VendorService;
use Illuminate\Http\Request;

class VendorController extends CommonController
{
    /**
     * @var VendorService
     */
    private $vendorService;

    public function __construct(VendorService $vendorService)
    {
        parent::__construct();
        $this->vendorService = $vendorService;
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $vendor = auth()->guard('vendor')->user();

        $status = $this->vendorService->changePassword($vendor, $request);

        return response()->json($status);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'business_name'     => 'required|string',
            'first_name'     => 'required|string',
            'last_name'     => 'required|string',
            'image'    => 'nullable|file|max:5120|mimes:jpg,jpeg,png',
            'email'    => 'required|email',
            'phone'    => 'required|min:9|max:10',
            'password' => 'nullable|string|confirmed',
            'address'    => 'required|string',
            'lat'    => 'required|string',
            'long'    => 'required|string',


        ]);

        $vendor = auth()->guard('vendor')->user();

        $vendor  = $this->vendorService->updateByModel($vendor, $data);

        return new AdminResource($vendor);
    }
}
