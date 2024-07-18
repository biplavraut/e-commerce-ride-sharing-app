<?php

namespace App\Services;

use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Resources\Vendor\VendorResource;
use App\Services\ModelService;
use App\Vendor;
use Illuminate\Support\Facades\Hash;

class VendorService extends ModelService
{
    const MODEL = Vendor::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function delete($id)
    {
        $user = parent::delete($id);
        $user->deleteImage();

        return $user;
    }

    public function changePassword($vendor, ChangePasswordRequest $request)
    {
        if (Hash::check($request->old_password, $vendor->password)) {
            if ($this->updateByModel($vendor, ['password' => $request->new_password])) {
                return [
                    'status'  => true,
                    'message' => 'Your password has been changed successfully.',
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => 'Sorry, your password could not be changed. Please try again later.',
                ];
            }
        }

        return [
            'status'  => false,
            'message' => 'Your old password did not match. Please try again.',
        ];
    }

    public function getNotification($user)
    {
        return $user->notifications;
    }

    public function getAdvancedVendors($keyword)
    {
        // if (!$keyword) {
        //     return collect([]);
        // }

        $lowerKeyword = strtolower($keyword);
        $upperKeyword = strtoupper($keyword);

        return VendorResource::collection(
            $this->query()
                ->where('business_name', 'LIKE', $keyword . '%')
                ->orWhere('email', 'LIKE', "%$keyword%")
                ->orWhere('phone', 'LIKE', "%$keyword%")->paginate(10)
        );
    }

    public function getUnverifiedCount()
    {
        return $this->query()->where('verified', 0)->count();
    }
}
