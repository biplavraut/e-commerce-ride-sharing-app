<?php

use Faker\Generator as Faker;

$factory->define(\App\Social::class, function (Faker $faker) {
	return [
		'name' => $faker->userName,
		'url'  => $faker->url,
		'icon' => 'camera.png',
	];
});
