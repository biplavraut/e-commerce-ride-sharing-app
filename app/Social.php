<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class Social extends Model implements ImageableContract
{
	use Imageable;

	public $columnsWithTypes = [
		'name'  => 'string',
		'url'   => 'string',
		'order' => 'string',
		'icon'  => 'image',
	];
}
