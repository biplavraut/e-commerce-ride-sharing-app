<?php

namespace App\Caches;

class CategoryCache extends ModelCache implements ModelCacheContract
{
	const CACHE_KEY = 'category';

	public function cacheKey(): string
	{
		return 'category';
	}
}
