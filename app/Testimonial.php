<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model implements ImageableContract
{
	use Imageable, Routeable;

	public $columnsWithTypes = [
		'name'    => 'string',
		'message' => 'string',
		'image'   => 'image',
	];
}
