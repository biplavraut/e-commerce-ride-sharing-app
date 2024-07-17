<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
	public function getImage($imageName, $imageSize = null) {
		if($imageName=='watermarked'){
			return response()->download(storage_path("app/public/images/watermarked/{$imageSize}"), null, [], null);
		}

		if(!$imageSize) {
			return response()->download(storage_path("app/public/images/{$imageName}"), null, [], null);
		}

		return response()->download(storage_path("app/public/images/modified/{$imageName}/{$imageSize}"), null, [], null);
	}

	public function test(Request $request)
	{
		return Storage::disk('local')->size('mr-jhole.mp4');
		return Storage::disk('s3')->temporaryUrl(
			'mr-jhole.mp4', now()->addMinutes(1)
		);
		dd($request->file('file'));
		$request->file('file')->store('videos','s3');

		return "success";
	}
}