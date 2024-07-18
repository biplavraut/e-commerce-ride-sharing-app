<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReviewRating extends Model
{
    protected $guarded = ['id'];
    /**
     * Columns of the table with data type e.g. 'name' => 'string'
     */
    public $columnsWithTypes = [
        'vendor_id' => 'string',
        'product_id' => 'string',
        'user_id' => 'string',
        'review' => 'string',
        'rating' => 'string',
        'likes' => 'string',
        'anonymously' => 'string',
        'verified' => 'boolean'
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

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function hasImage()
    {
        return $this->images->count() > 0;
    }

    public function isVerified()
    {
        return $this->verified == 1;
    }

    public function verify()
    {
        $this->verified = true;
        $this->save();
    }

    public function setAnonymouslyAttribute($value)
    {
        $this->attributes['anonymously'] = $value == true ? 1 : 0;
    }
}
