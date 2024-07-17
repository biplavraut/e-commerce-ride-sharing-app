<?php

namespace App\Custom\Traits;

use App\Custom\RoutePresenter;

trait Routeable
{
    public function getRouteAttribute()
    {
        return new RoutePresenter($this);
    }
}
