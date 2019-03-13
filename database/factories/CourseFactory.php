<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
    $date = rand(1, 60);
    return [
        'long_id' => strtoupper(str_random(6)),
        'description' => $faker->sentence,
        'date' => date('d/m/Y') . ' al ' . date('d/m/Y'),
        'start_date' => Carbon::now()->addDays($date),
        'end_date' => Carbon::now()->addDays($date + rand(1, 6)),
        'limit' => rand(10, 20),
    ];
});
