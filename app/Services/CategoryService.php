<?php

namespace App\Services;

use App\Caches\ModelCacheContract;
use App\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryService extends ModelService implements ModelCacheContract
{
    const MODEL = Category::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        return $this->query()->orderBy('name')->paginate($limit, $columns);
    }

    public function getForDataTable($columns = ['*'])
    {
        $query = $this->query()->select($columns)->withCount('children')->latest()->root();

        return DataTables::eloquent($query)
            ->addColumn('image50', function (Category $model) {
                return $model->image(50, 50);
            })
            ->addColumn('action', function (Category $model) {
                return (string) view('admin.extras.actions', ['model' => $model]);
            })
            ->toJson();
    }

    /**
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function store($options = [])
    {
        $model = $this->saveCategory($options);

        $this->saveSubCategoriesIfPresent($options, $model);

        return $model;
    }

    /**
     * @param       $id
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function update($id, $options = [])
    {
        $model = $this->updateCategory($id, $options);

        $this->updateOrCreateSubCategories($options, $model);

        return $model->load('children');
    }

    public function delete($id)
    {
        /** @var Category $category */
        $category = $this->query()->with(['children', 'parentCategory'])->findOrFail($id);
        $category->deleteImage();

        $category->children->map(function (Category $subCategory) {
            $subCategory->deleteImage();
        });

        $this->changeParentStatus($category);

        $category->delete();

        return $category;
    }

    /**
     * @param $options
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    private function saveCategory($options)
    {
        $model = $this->modelCrud->setModel($this->getNewModel())->save([
            'name'   => $options['name'],
            'subtitle'   => $options['subtitle'],
            'enabled'   => $options['enabled'],
            'slug'   => $options['slug'],
            'parent' => $options['has_sub_categories'] ?? false,
            'image'  => $options['image'] ?? null,
        ]);

        return $model;
    }

    /**
     * @param $options
     * @param $model
     *
     * @throws \Exception
     */
    private function saveSubCategoriesIfPresent($options, $model): void
    {
        foreach ($options['sub_names'] ?? [] as $key => $subName) {
            $this->modelCrud->setModel($this->getNewModel())->save([
                'parent_id' => $model->id,
                'name'      => $subName,
                'slug'      => $options['sub_slugs'][$key],
                'image'     => $options['sub_images'][$key] ?? null,
            ]);
        }
    }

    /**
     * @param $id
     * @param $options
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function updateCategory($id, $options)
    {
        /** @var Category $model */
        $model = $this->query()->with('children')->findOrFail($id);

        $model = $this->modelCrud->setModel($model)->save([
            'name'   => $options['name'],
            'subtitle'   => $options['subtitle'],
            'enabled'   => $options['enabled'],
            'slug'   => $options['slug'],
            'parent' => $options['has_sub_categories'] ?? false,
            'image'  => $options['image'] ?? null,
        ]);

        return $model;
    }

    /**
     * @param $options
     * @param $model
     *
     * @throws \Exception
     */
    private function updateOrCreateSubCategories($options, $model): void
    {
        foreach ($options['sub_names'] ?? [] as $key => $subName) {
            /** @var Category $subModel */
            $subModel = $this->find($options['sub_ids'][$key]);
            $this->modelCrud->setModel($subModel ?: $this->getNewModel())
                ->save([
                    'parent_id' => $model->id,
                    'name'      => $subName,
                    'slug'      => $options['sub_slugs'][$key],
                    'image'     => $options['sub_images'][$key] ?? null,
                ]);
        }
    }

    /**
     * Remove from parent if it has no children
     *
     * @param $category
     */
    private function changeParentStatus($category): void
    {
        if ($category->isChild() && $category->parentCategory->children()->count() == 1) {
            $category->parentCategory->update(['parent' => 0]);
        }
    }

    public function getCategories()
    {
        return $this->query()->latest()->get();
    }
}
