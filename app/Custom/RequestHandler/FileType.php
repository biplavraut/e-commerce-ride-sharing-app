<?php

namespace App\Custom\RequestHandler;

use App\Custom\Contracts\FileableContract;
use App\Custom\FileUpload;

class FileType implements RequestHandlerInterface
{
	/**
	 * Return request value
	 *
	 * @param $column
	 * @param $value
	 * @param FileableContract $model
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public function parse($column, $value, $model)
	{
		$requestData = $value;

		if (!($model instanceof FileableContract)) {
			throw new \Exception('Please add FileableContract in class: ' . get_class($model));
		}

		// if data is present
		if ($requestData) {
			$fileName = $this->uploadFile($requestData, $model, $column);

			if ($this->isFromUpdate($model)) {
				$model->deleteFile($column);
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
	 * @return bool
	 */
	private function isFromUpdate($model): bool
	{
		return $model->exists;
	}

	/**
	 * Upload file
	 *
	 * @param $requestData
	 * @param FileableContract $model
	 * @param $column
	 *
	 * @return string
	 */
	private function uploadFile($requestData, $model, $column): string
	{
		$fileUpload = new FileUpload();
		$fileName   = $fileUpload->setFile($requestData)
		                         ->setDestination($model->fileUploadFolder($column))
		                         ->handle();

		return $fileName;
	}
}
