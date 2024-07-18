<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use Illuminate\Database\Eloquent\Model;

class News extends Model implements ImageableContract
{
	use Imageable;

	public $columnsWithTypes = [
		'name'        => 'string',
		'slug'        => 'string',
		'description' => 'string',
		'image'       => 'image',
	];
}
