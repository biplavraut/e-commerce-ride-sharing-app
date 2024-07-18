<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'title'       => $title,
        'slug'        => str_slug($title),
        'description' => $faker->sentence(10, false),
    ];
});
