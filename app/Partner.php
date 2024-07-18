<?php

namespace App;

use Carbon\Carbon;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use App\Custom\Contracts\ImageableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = [];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'name' => 'string',
        'image' => 'image',
        'hide' => 'boolean',
        'order' => 'string',
        'expire_in' => 'string',
        'vendor_id' => 'string',
        'has_branches' => 'boolean',
        'parent_id' => 'string'
    ];

    public function getFixedAttribute()
    {
        if (Carbon::parse($this->expire_in)->gte(Carbon::now())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the vendor that owns the Partner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id')->withDefault(['id' => null, 'name' => 'N\\A']);
    }
}
