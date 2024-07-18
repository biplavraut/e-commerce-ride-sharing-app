<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Caches\CategoryCache;
use App\Exports\CategoryExport;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Resources\Admin\CategoryResource;

class CategoryController extends CommonController
{
    protected $cacheKey = CategoryCache::CACHE_KEY;

    /**
     * @var CategoryService
     */
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->query()->orderBy('order')->get();

        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->store($request->all());

        $this->forgetIndexCache();

        return new CategoryResource($category);
    }

    public function show($id)
    {
        $category      = $this->categoryService->with('children')->find($id);
        $subCategories = $category->children->map(function ($child) {
            return [
                'id'    => $child->id,
                'name'  => $child->name,
                'slug'  => $child->slug,
                'enabled'  => $child->enabled,
                'image' => $child->cropImage(200, 200),
            ];
        });

        return response()->json(['category' => $category, 'subCategories' => $subCategories]);
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = $this->categoryService->update($id, $request->all());

        $this->forgetIndexCache();

        return response()->json(['message' => 'Success', 'category' => $category]);
    }

    public function destroy($id)
    {
        $this->categoryService->delete($id);

        $this->forgetIndexCache();

        return response()->json(['message' => 'Successfully Deleted.']);
    }

    public function excelExport()
    {
        return (new CategoryExport())->download('categories.xlsx');
    }

    public function getAll()
    {
        return CategoryResource::collection($this->categoryService->getCategories());
    }

    public function changeOrder()
    {
        foreach (request()->all() as $key => $orderedCategoryId) {
            $service =  Category::where('id', $orderedCategoryId)->first();
            $service->update(['order' => $key + 1]);
        }
    }
}
