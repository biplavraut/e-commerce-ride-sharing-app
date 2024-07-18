<?php

namespace App\Caches;

use App\Services\ModelService;
use Illuminate\Support\Facades\Cache;

abstract class ModelCache
{
	const CACHE_FOR_HALF_AN_HOUR = 30;
	const CACHE_FOR_AN_HOUR = 60;
	const CACHE_FOR_A_DAY = 1440;
	const CACHE_FOR_A_MONTH = 43200;

	/**
	 * @var ModelService
	 */
	protected $service;

	public function __construct($service)
	{
		$this->service = $service;
	}

	public abstract function cacheKey(): string;

	public function getForIndex($paginationLimit)
	{
		return Cache::remember(
			$this->cacheKey() . '.index',
			self::CACHE_FOR_A_DAY,
			function () use ($paginationLimit) {
				return $this->service->getforIndex($paginationLimit);
			});
	}

	public function thisMonthData(): array
	{
		$time = now()->diffInMinutes(now()->endOfMonth()->addDay(), false);
		$key  = $this->cacheKey() . '.this-month-data';

		return Cache::remember(
			$key,
			$time,
			function () {
				$this->service->thisMonthData();
			});
	}
}
