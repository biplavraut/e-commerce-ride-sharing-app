<?php

namespace App\Custom\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Fileable
{
	public function deleteFile(string $columnName = 'file'): void
	{
		Storage::delete($this->filePath($columnName));
	}

	public function fileUploadFolder(string $columnName = 'file'): string
	{
		$className  = Str::kebab(class_basename($this));
		$columnName = Str::plural($columnName);

		return "files/{$className}/{$columnName}";
	}

	public function fileUrl(string $columnName = 'file'): string
	{
		return myAsset('storage/' . $this->filePath($columnName));
	}

	private function filePath(string $columnName): string
	{
		$originalName = $this->getOriginal($columnName);
		$uploadFolder = $this->fileUploadFolder($columnName);

		return "{$uploadFolder}/{$originalName}";
	}
}
