<?php

use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
    return [
        'long_id' => strtoupper(str_random(5)),
        'description' => $faker->sentence,
        'date' => date('d/m/Y').' al '.date('d/m/Y'),
        'limit' => rand(1, 20),
    ];
});
