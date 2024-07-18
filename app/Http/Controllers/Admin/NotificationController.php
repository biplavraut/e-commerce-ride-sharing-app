<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotificationResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
	public function index()
	{
		$admin = auth()->user();
		$data = [];
		if ($admin->type == 'admin' || $admin->type == 'superadmin') {
			$data = DB::table('notifications')->latest()->get();
		}
		//return $data;
		return NotificationResource::collection($data)->additional(['meta' => '', 'links' => '']);
		// return NotificationResource::collection(auth()->user()->notifications()->latest()->paginate(10));
	}

	public function latestNotifications()
	{
		return NotificationResource::collection(
			auth()->user()->notifications()->latest()->limit(4)->get()
		);
	}

	public function markAsRead($notificationId)
	{
		$notification = DB::table('notifications')->where('id', $notificationId)->update(['read_at' => now()]);
		return response(now()->toDateTimeString());
	}
}
