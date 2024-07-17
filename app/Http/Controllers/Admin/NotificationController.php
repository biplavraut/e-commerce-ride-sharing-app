<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\NotificationResource;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
	public function index()
	{
		return NotificationResource::collection(auth()->user()->notifications()->latest()->paginate(10));
	}

	public function latestNotifications()
	{
		return NotificationResource::collection(
			auth()->user()->notifications()->latest()->limit(4)->get()
		);
	}

	public function markAsRead($notificationId)
	{
		$notification = auth()->user()->notifications()->where('id', $notificationId)->update(['read_at' => now()]);

		return response(now()->toDateTimeString());
	}
}
