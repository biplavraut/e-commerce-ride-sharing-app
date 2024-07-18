<?php

namespace App\Custom\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait Imageable
{
	private $imagesNotToDelete = ['avatar.png', 'camera.png', 'loading.gif', 'no-image.png'];

	/**
	 * Delete image
	 *
	 * @param string $column
	 */
	public function deleteImage(string $column = 'image'): void
	{
		$originalName = $this->getOriginal($column);

		if ($originalName && !in_array($originalName, $this->imagesNotToDelete)) {
			$this->deleteRelatedImages($column);
			Storage::delete($this->imagePath($column));
		}
	}

	/**
	 * Set upload folder name after public/storage/
	 *
	 * @param string $column
	 *
	 * @return string
	 */
	public function imageUploadFolder(string $column = 'image'): string
	{
		$className = Str::kebab(class_basename($this));
		$column    = Str::plural($column);

		return "images/{$className}/{$column}";
	}

	/**
	 * Get image url
	 *
	 * @param string $column
	 *
	 * @return string
	 */
	public function imageUrl(string $column = 'image'): string
	{
		$originalName = $this->getOriginal($column);
		if (in_array($originalName, $this->imagesNotToDelete)) {
			if (env('FILESYSTEM_DRIVER') == 'public') {
				return myAsset("storage/images/{$originalName}");
			}
			return s3Asset("images/{$originalName}");
		}

		if (env('FILESYSTEM_DRIVER') == 'public') {
			return myAsset('storage/' . $this->imagePath($column));
		}
		return s3Asset($this->imagePath($column));
	}

	/**
	 * Get modified image
	 *
	 * @param int $x
	 * @param int $y
	 * @param string $column
	 *
	 * @param string $modificationType
	 *
	 * @return string
	 */
	private function modifyImage($x = 100, $y = 100, string $column = 'image', string $modificationType = 'crop'): string
	{
		$imageName = $this->getOriginal($column);

		$imagePath = $this->imagePath($column);
		if (!$imageName || !Storage::exists($imagePath)) {
			// return no-image.png url
			return $this->imageUrl($column);
		}


		if (env('FILESYSTEM_DRIVER') == 'public') {
			$img = Image::make(public_path("storage/{$imagePath}"));

			$extension = $img->extension == "jfif" ? 'jpg' : $img->extension;

			$fileNameWithoutExt     = $img->filename;
			$imageDestinationName   = $x . "x" . ($y ?? "auto") . "." . $extension;
			$imageDestinationFolder = $this->imageUploadFolder($column) . '/modified/' . $fileNameWithoutExt . '/' . $modificationType;
			$imageDestinationPath   = $imageDestinationFolder . '/' . $imageDestinationName;
		} else {
			// $img = Image::make(Storage::disk('s3')->url($imagePath));

			// $explodes = explode("/",$imagePath);
			// $file = $explodes[count($explodes)-1];

			// $extension = explode(".",$file)[1] == "jfif" ? 'jpg' : explode(".",$file)[1];

			// $fileNameWithoutExt     = explode(".",$file)[0];
			// $imageDestinationName   = $x . "x" . ($y ?? "auto") . "." .$extension;
			// $imageDestinationFolder = $this->imageUploadFolder($column) . '/modified/' . $fileNameWithoutExt . '/' . $modificationType;
			// $imageDestinationPath   = $imageDestinationFolder . '/' . $imageDestinationName;

		}



		if (env('FILESYSTEM_DRIVER') == 'public') {
			if (!Storage::exists($imageDestinationFolder)) {
				Storage::makeDirectory($imageDestinationFolder);
			}

			if (!Storage::exists($imageDestinationPath)) {
				switch ($modificationType) {
					case 'resize':
						$img->resize($x, $y, function ($constraint) {
							$constraint->aspectRatio();
							$constraint->upsize();
						});
						break;
					case 'cropwatermark':
						$img->fit($x, $y);
						// $img->fit($x, $y);
						// $img->insert('storage/images/watermark.png', 'bottom-right', 10, 10);
						break;
					case 'watermark':
						$img->fit($x, $y);
						// $img->insert('storage/images/watermark.png', 'bottom-right', 10, 10);
						break;
					default:
						$img->fit($x, $y);
						break;
				}

				$img->save(public_path("storage/{$imageDestinationPath}"));
			}

			return myAsset("storage/{$imageDestinationPath}");
		}

		// if (!in_array($imageDestinationFolder, Storage::disk('s3')->directories('parentDirectory'))) {
		// 	Storage::disk('s3')->makeDirectory($imageDestinationFolder);
		// }


		// if (!Storage::disk('s3')->exists($imageDestinationPath)) {
		// 	switch ($modificationType) {
		// 		case 'resize':
		// 			$img->resize($x, $y, function ($constraint) {
		// 				$constraint->aspectRatio();
		// 				$constraint->upsize();
		// 			});
		// 			break;
		// 		case 'cropwatermark':
		// 			$img->fit($x, $y);
		// 			// $img->fit($x, $y);
		// 			// $img->insert('storage/images/watermark.png', 'bottom-right', 10, 10);
		// 			break;
		// 		case 'watermark':
		// 			$img->fit($x, $y);
		// 			// $img->insert('storage/images/watermark.png', 'bottom-right', 10, 10);
		// 			break;
		// 		default:
		// 			$img->fit($x, $y);
		// 			break;
		// 	}


		// 	$img->save(Storage::disk('s3')->put($imageDestinationPath, $img));
		// }

		return s3Asset($imagePath);
	}

	/**
	 * Get cropped image
	 *
	 * @param int $x
	 * @param int $y
	 * @param string $column
	 *
	 * @return string
	 */
	public function cropImage(int $x = 100, int $y = 100, string $column = 'image'): string
	{
		return $this->modifyImage($x, $y, $column);
	}


	public function cropWithWatermarkImage(int $x = 100, int $y = 100, string $column = 'image'): string
	{
		return $this->modifyImage($x, $y, $column, 'cropwatermark');
	}

	public function watermarkImage(int $x = 100, int $y = 100, string $column = 'image'): string
	{
		return $this->modifyImage($x, $y, $column, 'watermark');
	}

	public function watermarkInOrginalImage(string $column = 'image'): string
	{
		return $this->modifyImage(null, null, $column, 'watermark');
	}

	/**
	 * Get resized image
	 *
	 * @param int $x
	 * @param int $y
	 * @param string $column
	 *
	 * @return string
	 */
	public function resizeImage($x = 100, $y = 100, string $column = 'image'): string
	{
		return $this->modifyImage($x, $y, $column, 'resize');
	}

	// deletes images starting with the same name as current image name
	private function deleteRelatedImages(string $column = 'image'): void
	{
		$imageName = $this->getOriginal($column);
		// if we don't have previous image then no need to delete it.
		if ($imageName && Storage::exists($this->imagePath($column))) {
			$modifiedImageDirectory = $this->imageUploadFolder($column) . '/modified/' . explode('.', $imageName)[0];

			Storage::deleteDirectory($modifiedImageDirectory);
		}
	}

	public function imagePath(string $column = 'image'): string
	{
		$originalName = $this->getOriginal($column);
		if (!$originalName) {
			return 'images/no-image.png';
		}

		$uploadFolder = $this->imageUploadFolder($column);

		return "{$uploadFolder}/{$originalName}";
	}

	public function imagePath1(string $column = 'image'): string
	{
		$originalName = $this->getOriginal($column);
		if (!$originalName) {
			return 'images/no-image.png';
		}

		$uploadFolder = $this->imageUploadFolder($column);

		return "{$uploadFolder}";
	}

	public function getImageAttribute($value): string
	{
		return $this->imageUrl();
	}

	public function getOriginalImage(string $column = 'image'): string
	{
		$imageName = $this->getOriginal($column);

		return !is_null($imageName)
			? route('get-image', [$imageName])
			: route('get-image', ['no-image.png']);
	}

	public function addWatermark($watermarkImage = null, $column = 'image'): string
	{
		$imageName = $this->getOriginal($column);

		$imagePath = $this->imagePath();

		if ($imageName && Storage::exists($imagePath)) {

			if (!Storage::exists("images/watermark.png")) {
				$watermarkImg = Image::make($imagePath . $watermarkImage);
				$watermarkImg->fit(50, 50, function ($constraint) {
					$constraint->upsize();
				});
				$watermarkImg->save($imagePath . "watermark.png");
			} else {
				$image = Image::make(public_path("storage/{$this->imagePath1()}/{$imageName}"));

				if (!Storage::exists("{$this->imagePath1()}/watermarked/")) {

					Storage::makeDirectory("{$this->imagePath1()}/watermarked/");
				}

				$image->insert('storage/images/watermark.png', 'bottom-right', 10, 10);

				$image->save("storage/{$this->imagePath1()}/watermarked/{$imageName}");
			}
		}
		return myAsset("storage/{$this->imagePath1()}/watermarked/{$imageName}");
	}
}
