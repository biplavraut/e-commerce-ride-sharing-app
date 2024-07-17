<?php

use Faker\Generator as Faker;

$factory->define(\App\Company::class, function (Faker $faker) {
	return [
		'name'             => $faker->company,
		'email'            => $faker->unique()->companyEmail,
		'established_date' => now(),
		'address'          => $faker->address,
		'phone'            => $faker->phoneNumber,
		'about'            => $faker->paragraph(15, false),
		'logo'             => null,
	];
});
