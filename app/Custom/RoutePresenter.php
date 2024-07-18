<?php

namespace App\Custom;

use Illuminate\Database\Eloquent\Model;

class RoutePresenter
{
    private $model;
    private $routeName;

    public function __construct(Model $model, $routeName = null)
    {
        $this->model     = $model;
        $this->routeName = $routeName ?: strtolower(class_basename($model));
    }

    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return $this->$key;
    }

    public function index(array $params = [])
    {
        return route("{$this->routeName}.index", $params);
    }

    public function create(array $params = [])
    {
        return route("{$this->routeName}.create", $params);
    }

    public function store(array $params = [])
    {
        return route("{$this->routeName}.store", $params);
    }

    public function show(array $params = [])
    {
        return route("{$this->routeName}.show", array_merge([$this->model->id], $params));
    }

    public function edit(array $params = [])
    {
        // return route('user.edit', $this->model);
        return route("{$this->routeName}.edit", array_merge([$this->model->id], $params));
    }

    public function update(array $params = [])
    {
        return route("{$this->routeName}.update", array_merge([$this->model->id], $params));
    }

    public function destroy(array $params = [])
    {
        return route("{$this->routeName}.destroy", array_merge([$this->model->id], $params));
    }
}
