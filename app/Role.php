<?php

namespace App;

use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Role extends Eloquent
{
    use Imageable, Routeable;

    protected $guarded = ['id'];

    public function name()
    {
        return ucfirst($this->name);
    }

    public function name_lowercase()
    {
        return strtolower($this->name);
    }

    public function name_camel_case()
    {
        return camel_case($this->name_lowercase());
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class)->withTimestamps();
    }
}
