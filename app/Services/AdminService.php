<?php

namespace App\Services;

use App\Admin;
use App\Http\Requests\Admin\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class AdminService extends ModelService
{
	const MODEL = Admin::class;


	public function getForIndex($limit = 20, $columns = ['*'])
	{
		return $this->query()->where('type', '!=', 'superadmin')->latest()->paginate($limit, $columns);
	}

	public function delete($id)
	{
		$user = parent::delete($id);
		$user->deleteImage();

		return $user;
	}

	public function changePassword($admin, ChangePasswordRequest $request)
	{
		if (Hash::check($request->old_password, $admin->password)) {
			if ($this->updateByModel($admin, ['password' => $request->new_password])) {
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
}
