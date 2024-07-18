<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GogoAd extends Model implements ImageableContract
{
    use Imageable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $columnsWithTypes = [
        'title' => 'string',
        'image' => 'image',
        'url' => 'string',
        'type' => 'string',
        'service_id' => 'string',
        'hide' => 'boolean',
    ];
    
    public function service(){
        return $this->belongsTo(Category::class, 'service_id');
    }
    
    public function serviceName()
    {
        $service = Category::select('name')->where('id', $this->service_id)->first();
        return $service->name;
    }
}
