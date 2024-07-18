<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionCategory extends Model
{
    use SoftDeletes;

    public $columnsWithTypes = [
        'title'        => 'string',
        'slug'        => 'string',
        'order'        => 'string',
        'layout' => 'string',
        'service_id' => 'string',
        'title_color' => 'string',
        'layout_color' => 'string',
    ];

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * Get the service that owns the ProductOptionCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'service_id')->withDefault(['id' => null, 'name' => 'N\\A']);
    }
}
