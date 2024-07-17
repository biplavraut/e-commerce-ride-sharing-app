<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductQa extends Model
{
    protected $guarded = ['id'];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'vendor_id' => 'string',
        'product_id' => 'string',
        'user_id' => 'string',
        'question' => 'string',
        'answer' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
