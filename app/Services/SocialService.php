<?php

namespace App\Services;

use App\Caches\ModelCacheContract;
use App\Social;

class SocialService extends ModelService implements ModelCacheContract
{
	const MODEL = Social::class;

	public function getForIndex($limit)
	{
		return $this->query()->orderBy('order')->paginate($limit);
	}

	public function delete($id)
	{
		$social = parent::delete($id);

		$social->deleteImage('icon');

		return $social;
	}

	public function changeOrder(array $orderedCategoryIds)
	{
		foreach ($orderedCategoryIds as $key => $orderedCategoryId) {
			$this->update($orderedCategoryId, ['order' => $key + 1]);
		}
	}
}