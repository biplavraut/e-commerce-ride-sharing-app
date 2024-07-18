<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    public function getMunicipalities()
    {
        return $this->id;
    }
}
