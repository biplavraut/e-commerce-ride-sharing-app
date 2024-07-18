<?php

namespace App\Custom\Contracts;

interface FileableContract
{
	/**
	 * Delete file
	 *
	 * @param string $column
	 *
	 * @return void
	 */
	public function deleteFile(string $column = 'file'): void;

	/**
	 * Set upload folder name after public/storage/
	 *
	 * @param string $column
	 *
	 * @return string
	 */
	public function fileUploadFolder(string $column = 'file'): string;

	/**
	 * Get file url
	 *
	 * @param string $columnName
	 *
	 * @return string
	 */
	public function fileUrl(string $columnName = 'file'): string;
}
