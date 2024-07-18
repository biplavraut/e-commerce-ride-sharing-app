<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Company extends Eloquent implements ImageableContract
{
	use Imageable, Routeable;

	protected $guarded = ['id'];

	protected $dates = ['established_date'];

	public $columnsWithTypes = [
		'name'             => 'string',
		'email'            => 'string',
		'established_date' => 'date',
		'address'          => 'string',
		'phone'            => 'string',
		'about'            => 'string',
		'rider_tac'            => 'string',
		'user_tac'            => 'string',
		'vendor_tac'            => 'string',
		'logo'             => 'image',
	];

	public function getLogoAttribute($value)
	{
		return $this->imageUrl('logo');
	}

	public function name()
	{
		return ucfirst($this->name);
	}

	public function logo()
	{
		return $this->logo != null
			? asset($this->imagePath . $this->logo)
			: asset($this->imagePath . "no-image.png");
	}

	public function logo_path()
	{
		return public_path('images/' . $this->logo);
	}
}
