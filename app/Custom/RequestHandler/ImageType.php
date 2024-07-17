<?php

namespace App\Custom\RequestHandler;

use App\Custom\Contracts\ImageableContract;
use App\Custom\FileUpload;

class ImageType implements RequestHandlerInterface
{
	/**
	 * Return request value
	 *
	 * @param $column
	 * @param $value
	 * @param ImageableContract $model
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public function parse($column, $value, $model)
	{
		$requestData = $value;

		if (!($model instanceof ImageableContract)) {
			throw new \Exception('Please add ImageableContract in class: ' . get_class($model));
		}

		// if data is present
		if ($requestData) {
			$fileName = $this->uploadImage($model, $requestData, $column);

			if ($this->isFromUpdate($model)) {
				$model->deleteImage($column);
			}

			return $fileName;
		}

		// if data is not present & is form update
		if ($this->isFromUpdate($model)) {
			return $model->getOriginal($column);
		}

		return null;
	}

	/**
	 * Check if data is from update
	 *
	 * @param $model
	 *
	 * @return boolean
	 */
	private function isFromUpdate($model)
	{
		return $model->exists;
	}

	/**
	 * Upload image
	 *
	 * @param ImageableContract $model
	 * @param $requestData
	 *
	 * @param $column
	 *
	 * @return string|null
	 */
	private function uploadImage($model, $requestData, $column)
	{
		$fileUpload = new FileUpload();
		$fileName   = $fileUpload->setFile($requestData)
		                         ->setDestination($model->imageUploadFolder($column))
		                         ->handle();

		return $fileName;
	}
}