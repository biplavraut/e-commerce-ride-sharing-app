<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the builder factory definitions for
| your application. Factories provide a convenient way to generate new
| builder instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
	return [
		'first_name'           => $faker->name,
		'last_name'           => $faker->name,
		'email'          => $faker->unique()->safeEmail,
		'password'       => bcrypt('admin1'),
		'image'          => 'avatar.png',
		'phone'          => $faker->phoneNumber,
		'remember_token' => str_random(10),
		'verified'       => 1,
	];
});
