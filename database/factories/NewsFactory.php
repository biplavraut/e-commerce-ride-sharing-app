<?php

use Faker\Generator as Faker;

$factory->define(App\News::class, function (Faker $faker) {
	$title = $faker->sentence(7, false);

	return [
		'name'        => $title,
		'slug'        => str_slug($title),
		'image'       => 'camera.png',
		'description' => $faker->paragraph(15, false),
	];
});
