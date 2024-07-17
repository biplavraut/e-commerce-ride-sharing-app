<?php

use Faker\Generator as Faker;

$factory->define(\App\Category::class, function (Faker $faker) {
	$name = $faker->sentence(3, false);

	return [
		'name'   => $name,
		'slug'   => str_slug($name),
		'image'  => 'camera.png',
		'parent' => 0,
	];
});
