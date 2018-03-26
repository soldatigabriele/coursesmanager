<?php

use Faker\Generator as Faker;

$factory->define(App\Newsletter::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'region_id' => App\Region::inRandomOrder()->first()->id,
        'active' => 1,
        'meta' => json_encode(['ip'=>$faker->ipv4]),
    ];
});
