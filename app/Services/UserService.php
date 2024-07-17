<?php

namespace App\Services;

use App\User;

class UserService extends ModelService
{
	const MODEL = User::class;

	public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->orderBy("id","desc")->paginate($limit, $columns);
    }

	public function delete($id)
	{
		$user = parent::delete($id);
		$user->deleteImage();

		return $user;
	}

	public function getNotification($user)
	{
		return $user->notifications;
	}
}
