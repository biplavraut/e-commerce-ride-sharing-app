<?php

namespace App\Caches;

interface ModelCacheContract
{
	public function getForIndex($paginationLimit);

	public function thisMonthData();
}