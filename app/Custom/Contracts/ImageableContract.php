<?php

namespace App\Custom\Contracts;

interface ImageableContract
{
	/**
	 * Delete file
	 *
	 * @param string $column
	 *
	 * @return void
	 */
	public function deleteImage(string $column = 'image'): void;

	/**
	 * Set upload folder name after public/storage/
	 *
	 * @param string $column
	 *
	 * @return string
	 */
	public function imageUploadFolder(string $column = 'image'): string;

	/**
	 * Get file url
	 *
	 * @param string $column
	 *
	 * @return string
	 */
	public function imageUrl(string $column = 'image'): string;

	/**
	 * Get cropped image
	 *
	 * @param int $x
	 * @param int $y
	 * @param string $column
	 *
	 * @return mixed
	 */
	public function cropImage(int $x = 100, int $y = 100, string $column = 'image'): string;

	/**
	 * Get resized image
	 *
	 * @param int $x
	 * @param int $y
	 * @param string $column
	 *
	 * @return mixed
	 */
	public function resizeImage($x = 100, $y = 100, string $column = 'image'): string;
}
