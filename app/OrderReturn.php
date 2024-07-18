<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReturn extends Model
{
    //
    protected $guarded = [];

    public $columnsWithTypes = [
        'ticket' => 'string',
        'order_item_id' => 'string',
        'order_id' => 'string',
        'user_id' => 'string',
        'vendor_id' => 'string',
        'product_id' => 'string',
        'reason' => 'string',
        'quantity' => 'string',
        'status' => 'string',
        'remarks' => 'string',
        'status' => 'boolean'
    ];

    /**
     * Get the order that owns the OrderFeedback
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor', 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
}
