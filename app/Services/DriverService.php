<?php

namespace App\Services;

use App\Driver;
// use App\Http\Requests\ChangePasswordRequest;
use App\Services\ModelService;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\ChangePasswordRequest;

class DriverService extends ModelService
{
    const MODEL = Driver::class;

    public function getForIndex($limit = 20, $columns = ['*'], $verified = false)
    {
        // if ($verified) {
        //     return $this->query()->where('verified', 1)->whereHas('vehicleDetail')->latest()->paginate($limit, $columns);
        // }
        return $this->query()->where('verified', 0)->latest()->paginate($limit, $columns);
    }

    public function delete($id)
    {
        $user = parent::delete($id);
        $user->deleteImage();

        return $user;
    }

    public function changePassword($driver, ChangePasswordRequest $request)
    {
        if (Hash::check($request->old_password, $driver->password)) {
            if ($this->updateByModel($driver, ['password' => $request->new_password])) {
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

    public function getDrivers($name, $interest)
    {
        return $this->query()
            ->where('interested_in', $interest)
            ->where(function ($query) use ($name) {
                $query->orWhereRaw("concat(first_name, ' ', last_name) like '%$name%' ");
                $query->orWhere('phone', 'LIKE', $name . '%');
                $query->orWhere('email', 'LIKE', $name . '%');
                $query->orWhere('refer_code', $name);
            })->paginate(10);
    }

    public function getAssociatedDriver($interest)
    {
        return $this->query()->orderBy('id', 'desc')
            ->where('interested_in', $interest)
            ->where("is_associated_rider", 1)
            ->where("is_blocked", 0)
            ->where("verified", 1)
            ->with(['todayTotalAssigned', 'todayTotalDelivered'])->get();
    }

    public function getCount()
    {
        return $this->query()->count();
    }

    public function getUnverifiedCount()
    {
        return $this->query()->where('verified', 0)->count();
    }

    public function getUnverifiedRiderCount()
    {
        return $this->query()->where('interested_in', 'rider')->where('verified', 0)->count();
    }

    public function getActiveCount()
    {
        $active = 0;
        $verifiedRiders = $this->query()->where('verified', 1)->with('status')->get();
        foreach ($verifiedRiders as $key => $rider) {
            if ($rider->status) {
                if ($rider->status['status'] != "inactive") {
                    $active++;
                }
            }
        }
        return $active;
    }

    public function getRiderCount()
    {
        return $this->query()->where('interested_in', 'rider')->count();
    }
}
