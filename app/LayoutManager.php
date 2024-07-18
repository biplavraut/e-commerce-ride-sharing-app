<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutManager extends Model
{
    protected $guarded = [];

    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'name' => 'string',
        'model_id' => 'json',
        'service_id' => 'string',
        'model_type' => 'string',
        'order' => 'string',
    ];

    public function model()
    {
        return $this->morphTo('model');
    }

    public function getMe($id)
    {
        return $this->where('model_id', $id)->limit(1)->get();
    }

    public function getModelIdAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    public function getObjectAttribute()
    {
        if ($this->model_type == "App\Slider") {
            return $this->model_type::whereIn("id", $this->model_id)->where('category_id', $this->service_id)->get();
        }

        if ($this->model_type == "App\Deal") {
            return $this->model_type::whereIn("id", $this->model_id)->where('category_id', $this->service_id)->where('status', 1)
                ->where('from', '<=', date('Y-m-d H:i:s'))
                ->where('to', '>', date('Y-m-d H:i:s'))
                ->with(array('dealproducts' => function ($query) {
                    $query->with('product');
                }))
                ->get();
        }

        if ($this->model_type == "App\GogoAd") {
            return $this->model_type::whereIn("id", $this->model_id)->where('hide', 0)->where('service_id', $this->service_id)->get();
        }

        return $this->model_type::whereIn("id", $this->model_id)->where('service_id', $this->service_id)->orderBy('order')->get();
    }

    /**
     * Get the service that owns the LayoutManager
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'service_id');
    }
}
