<?php

use Faker\Generator as Faker;

$factory->define(App\Admin::class, function (Faker $faker) {
	return [
		'name'           => $faker->name,
		'email'          => $faker->unique()->safeEmail,
		'password'       => bcrypt('admin1'),
		'image'          => 'avatar.png',
		'verified'       => true,
		'type'           => 'admin',
		'remember_token' => str_random(10),
	];
});
