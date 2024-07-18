<?php

namespace App\Caches;

class SocialCache extends ModelCache implements ModelCacheContract
{
	const CACHE_KEY = 'social';

	public function cacheKey(): string
	{
		return 'social';
	}
}
