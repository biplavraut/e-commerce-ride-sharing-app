<?php

namespace App\Services;


use App\Image;
use App\Product;

class ImageService extends ModelService
{
    const MODEL = Image::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->latest()->paginate($limit, $columns);
    }

    public function deleteImage($id, $authId)
    {
        try {
            $image = Image::find($id);
            $product = Product::where('id', $image->model_id)->Where('vendor_id', $authId)->count();
            if ($product == 1) {
                $image->delete();
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function deleteProductImage($id)
    {
        try {
            $image = Image::find($id);
            $product = Product::where('id', $image->model_id)->count();
            if ($product == 1) {
                $image->delete();
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
